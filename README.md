Branch Cache
============

This is a TYPO3 CMS extension which adds a new entry in the context menu to
clear the cache for an entire branch. The extension is compatible with TYPO3 CMS
8

![](https://raw.github.com/cobwebch/branch_cache/master/Documentation/Screenshot.png)

Installation
------------

The installation is straightforward. Install the extension via composer,
activate in the Extension Manager, reload the whole Backend and start clearing
your cache! :)

```
composer require cobweb/branch-cache
```

As the extension is not yet on Packagist, the Git repository must be added
manually.

```
{
    "type": "git",
    "url": "https://github.com/cobwebch/branch_cache.git"
}
```

Source of inspiration https://github.com/TYPO3-extensions/sm_clearcachecm. Thanks Steffen!
