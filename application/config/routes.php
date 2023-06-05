<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$route['default_controller'] = 'auth';
$route['404_override'] = 'Err/my400';
$route['translate_uri_dashes'] = FALSE;

// For handle restful api's version
$route['api/v1/(.+)'] = 'api_v1/$1';

// If website owner wants to remove home from url when visiting the front-end
if ($route['default_controller'] == 'home') {
	$route['pricing'] = 'home/pricing';
	// $route['faq'] = 'home/faq';
	// $route['documentation'] = 'home/documentation';
	// $route['documentation_list/(.+)'] = 'home/documentation_list/$1';
	// $route['documentation_view/(.+)'] = 'home/documentation_view/$1';
	// $route['about'] = 'home/about';
	// $route['contact'] = 'home/contact';
}
