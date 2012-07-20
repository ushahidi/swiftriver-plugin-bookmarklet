<?php defined('SYSPATH') OR die('No direct script access');

// Bind the plugin to valid URLs
Route::set('bookmarklet', '<account>/bookmarklet')
	->defaults(array(
		'controller' => 'bookmarklet',
		'action'     => 'index'
	));