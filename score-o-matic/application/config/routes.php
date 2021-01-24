<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['setevent'] = 'pages/setevent';
$route['media_1'] = 'pages/media_1';
$route['media_2'] = 'pages/media_2';
$route['media_3'] = 'pages/media_3';
$route['media_4'] = 'pages/media_4';
$route['emp_1'] = 'pages/emp_1';
$route['emp_2'] = 'pages/emp_2';
$route['emp_3'] = 'pages/emp_3';
$route['emp_4'] = 'pages/emp_4';
$route['score_m'] = 'pages/score_m';
$route['score_1'] = 'pages/score_1';
$route['score_2'] = 'pages/score_2';
$route['score_3'] = 'pages/score_3';
$route['final_result'] = 'pages/final_result';
$route['team_info'] = 'pages/team_info';
$route['finalize_round'] = 'pages/finalize_round';
$route['team_update'] = 'pages/team_update';
$route['default_controller'] = 'pages/view';
$route['(:any)'] = 'pages/$1';
