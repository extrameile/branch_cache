/**
 * Module: TYPO3/CMS/BranchCache/ContextMenuActions
 *
 * @exports TYPO3/CMS/BranchCache/ContextMenuActions
 */
define(function() {
	'use strict';

	/**
	 * @exports TYPO3/CMS/BranchCache/ContextMenuActions
	 */
	var ContextMenuActions = {};

	/**
	 * @param {string} table
	 * @param {int} uid of the page
	 */
	ContextMenuActions.clearBranchCache = function(table, uid) {
		if (table === 'pages') {
			var url = TYPO3.settings.ajaxUrls['clear_branch_cache'];
			url += '&id=' + uid;
			$.ajax(url).always(function(result) {
				if (result) {
					top.TYPO3.Notification.success(result.title);
				} else {
					top.TYPO3.Notification.error(result.title);
				}
			});
		}
	};

	return ContextMenuActions;
});
