<?php
defined('TYPO3_MODE') or die();

if (TYPO3_MODE === 'BE') {
    $GLOBALS['TYPO3_CONF_VARS']['BE']['ContextMenu']['ItemProviders'][1523366345] = \Cobweb\BranchCache\ContextMenu\ItemProvider::class;

    $GLOBALS['TYPO3_CONF_VARS']['SC_OPTIONS']['typo3/backend.php']['constructPostProcess'][] = \Cobweb\BranchCache\Hook\BackendControllerHook::class . '->addLabels';

//    $iconRegistry->registerIcon($item['iconIdentifier'], SvgIconProvider::class, [
//        'source' => $icon,
//    ]);
//
//    /** @var \TYPO3\CMS\Core\Imaging\IconRegistry $iconRegistry */
//    $iconRegistry = \TYPO3\CMS\Core\Utility\GeneralUtility::makeInstance(\TYPO3\CMS\Core\Imaging\IconRegistry::class);
//    foreach ($icons as $key => $icon) {
//        $iconRegistry->registerIcon(
//            'extensions-messenger-' . $key,
//            \TYPO3\CMS\Core\Imaging\IconProvider\BitmapIconProvider::class,
//            [
//                'source' => $icon
//            ]
//        );
//    }
//    unset($iconRegistry);
}
