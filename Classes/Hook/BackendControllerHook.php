<?php

namespace Cobweb\BranchCache\Hook;

/*
 * This file is part of the Cobweb/BranchCache project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3\CMS\Backend\Controller\BackendController;
use TYPO3\CMS\Core\Page\PageRenderer;
use TYPO3\CMS\Core\Utility\GeneralUtility;

/**
 * This class adds new labels to the Backend
 */
class BackendControllerHook
{
    /**
     * @param array $configuration
     * @param BackendController $backendController
     */
    public function addLabels(array $configuration, BackendController $backendController)
    {
        /** @var PageRenderer $pageRenderer */
        $pageRenderer = $pageRenderer = GeneralUtility::makeInstance(PageRenderer::class);

        // Add language labels for ExtDirect
        $pageRenderer->addInlineLanguageLabelArray(
            [
                'clear.branch.cache.error' => $this->getLanguageService()->sL('LLL:EXT:branch_cache/Resources/Private/Language/locallang.xlf:clear.branch.cache.error'),
                'clear.branch.cache.success' => $this->getLanguageService()->sL('LLL:EXT:branch_cache/Resources/Private/Language/locallang.xlf:clear.branch.cache.success'),
            ],
            true
        );
    }

    /**
     * Returns LanguageService
     *
     * @return \TYPO3\CMS\Lang\LanguageService
     */
    protected function getLanguageService()
    {
        return $GLOBALS['LANG'];
    }
}
