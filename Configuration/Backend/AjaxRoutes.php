<?php

/**
 * Definitions for routes provided by EXT:branch_cache
 * Contains all AJAX-based routes for entry points
 */

use Cobweb\BranchCache\Controller\ContextMenuController;

return [

    'clear_branch_cache' => [
        'path' => '/branch-cache/clear',
        'target' => ContextMenuController::class . '::clearBranchCacheAction'
    ],

];
