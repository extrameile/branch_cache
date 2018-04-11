<?php

namespace Cobweb\BranchCache\Controller;

/*
 * This file is part of the Cobweb/BranchCache project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use TYPO3\CMS\Backend\Tree\Pagetree\DataProvider;
use TYPO3\CMS\Backend\Tree\Pagetree\PagetreeNode;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * Actions to clear page cache of a whole branch
 *
 * The TYPO3 page tree implementation uses the noun "node" instead of "page".
 */
class ContextMenuController
{

    /**
     * Clear branch cache action
     *
     * @param ServerRequestInterface $request
     * @param ResponseInterface $response
     * @return ResponseInterface
     * @throws \Exception
     */
    public function clearBranchCacheAction(ServerRequestInterface $request, ResponseInterface $response): ResponseInterface
    {
        $nodeLimit = ($GLOBALS['TYPO3_CONF_VARS']['BE']['pageTree']['preloadLimit']) ? $GLOBALS['TYPO3_CONF_VARS']['BE']['pageTree']['preloadLimit'] : 999;

        /* @var PagetreeNode $node */
        $nodeData = ['id' => $request->getQueryParams()['id']];
        $node = GeneralUtility::makeInstance(PagetreeNode::class, (array)$nodeData);

        // Get uid of actual page
        $nodeUids = [
            $node->getId()
        ];

        // Get uids of subpages
        /* @var DataProvider $dataProvider */
        $dataProvider = GeneralUtility::makeInstance(DataProvider::class, $nodeLimit);
        $nodeCollection = $dataProvider->getNodes($node);
        $childNodeUids = $this->transformTreeStructureIntoFlatArray($nodeCollection);

        // Marge actual and child nodes
        $nodeUids = array_merge($nodeUids, $childNodeUids);

        $response->getBody()->write($this->performClearCache($nodeUids));
        return $response;
    }

    /**
     * Recursively transform the node collection from tree structure into a flat array
     *
     * @param \TYPO3\CMS\Backend\Tree\TreeNodeCollection $nodeCollection A tree of node
     * @param integer $level Recursion counter, used internaly
     * @return array Node uids of all child nodes
     */
    protected function transformTreeStructureIntoFlatArray($nodeCollection, $level = 0): array
    {
        if ($level > 99) {
            return [];
        }

        $nodeUids = [];
        /** @var \TYPO3\CMS\Backend\Tree\TreeNode $childNode */
        foreach ($nodeCollection as $childNode) {
            $nodeUids[] = $childNode->getId();
            if ($childNode->hasChildNodes()) {
                $nodeUids = array_merge($nodeUids, $this->transformTreeStructureIntoFlatArray($childNode->getChildNodes(), $level + 1));
            } else {
                $nodeUids[] = $childNode->getId();
            }
        }

        return $nodeUids;
    }

    /**
     * Perform the cache clearing using tcemain
     *
     * @param array $nodeUids Node uids where the page cache has to be cleared
     * @return boolean true, if clearing of cache was successful
     * @throws \Exception
     */
    protected function performClearCache($nodeUids = array())
    {
        if (empty($nodeUids)) {
            return true;
        }

        /* @var $dataHandler \TYPO3\CMS\Core\DataHandling\DataHandler */
        $dataHandler = GeneralUtility::makeInstance(\TYPO3\CMS\Core\DataHandling\DataHandler::class);
        $dataHandler->stripslashes_values = 0;
        $dataHandler->start(array(), array());
        foreach ($nodeUids as $nodeUid) {
            $dataHandler->clear_cacheCmd($nodeUid);
        }

        // Check for errors
        if (\count($dataHandler->errorLog)) {
            throw new \Exception(implode(chr(10), $dataHandler->errorLog));
        }

        return true;
    }
}
