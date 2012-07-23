<?php defined('SYSPATH') OR die('No direct script access');

// Bind the plugin to valid URLs
Route::set('bookmarklet_dashboard', '<account>/bookmarklet')
	->defaults(array(
		'controller' => 'bookmarklet',
		'action'     => 'index'
	));
Route::set('bookmarklet', 'bookmarklet(/<action>(/<id>))')
	->defaults(array(
		'controller' => 'bookmarklet',
		'action'     => 'bookmarklet'
	));
	
// Add dashboard link
Swiftriver_Event::add('swiftriver.dashboard.nav', function() {
	
	$nav = & Swiftriver_Event::$data;
	
	$nav[] = array(
		'id' => 'bookmarklet-navigation-link',
		'url' => '/bookmarklet',
		'label' => __('Bookmarklet')
	);
});