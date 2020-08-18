<?php

$EM_CONF[$_EXTKEY] = [
	'title' => 'Clear cache of a whole branch',
	'description' => 'Clears cache of a whole branch. One click is enough!',
	'version' => '1.0.0',
	'state' => 'stable',
	'author' => 'Cobweb Sarl',
	'author_email' => 'typo3@cobweb.ch',
    'autoload' => [
        'psr-4' => ['Cobweb\\BranchCache\\' => 'Classes']
    ],
	'constraints' => [
		'depends' => [
			'typo3' => '10.4.0-10.4.99',
        ],
    ],
];