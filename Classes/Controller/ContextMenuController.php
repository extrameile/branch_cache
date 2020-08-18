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
use TYPO3\CMS\Backend\Tree\Repository\PageTreeRepository;
use TYPO3\CMS\Core\Localization\LanguageService;
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
     * @return ResponseInterface
     * @throws \Exception
     */
    public function clearBranchCacheAction(ServerRequestInterface $request): ResponseInterface
    {
        $pageId = $request->getQueryParams()['id'];

        $pageTreeRepository = GeneralUtility::makeInstance(PageTreeRepository::class);
        $pages = $pageTreeRepository->getTree($pageId);

        $nodeUids = $this->transformTreeStructureIntoFlatArray([$pages]);
        $nodeUids = \array_unique($nodeUids);

        $result = $this->performClearCache($nodeUids);

        $title = $this->getLabel('clear.branch.cache.' . ($result ? 'success' : 'error'));

        return new \TYPO3\CMS\Core\Http\JsonResponse(['title' => $title]);
    }

    /**
     * Recursively transform the node collection from tree structure into a flat array
     *
     * @param array $pages A tree of node
     * @param integer $level Recursion counter, used internaly
     * @return array Node uids of all child nodes
     */
    protected function transformTreeStructureIntoFlatArray(array $pages = [], $level = 0): array
    {
        if ($level > 99) {
            return [];
        }

        $nodeUids = [];
        foreach ($pages as $childNode) {
            $nodeUids[] = $childNode['uid'];
            if (\is_array($childNode['_children']) && count($childNode['_children']) > 0) {
                $nodeUids = array_merge($nodeUids, $this->transformTreeStructureIntoFlatArray($childNode['_children'], $level + 1));
            } else {
                $nodeUids[] = $childNode['uid'];
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

    /**
     * @inheritdoc
     */
    public function getLabel(string $id): string
    {
        $locallangFileAndPath = 'LLL:EXT:branch_cache/Resources/Private/Language/locallang.xlf:' . $id;
        return $this->getLanguageService()->sL($locallangFileAndPath);
    }

    /**
     * Returns LanguageService
     *
     * @return LanguageService
     */
    protected function getLanguageService(): LanguageService
    {
        return $GLOBALS['LANG'];
    }
}
