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


//Forntend
$route['default_controller'] = 'bookmarkuserscontroller';


$route['bookmark-submit']= 'bookmarkuserscontroller/save_bookmark';
$route['catalogue']= 'bookmarkuserscontroller/catelog';
$route['contact']= 'bookmarkuserscontroller/contact';
$route['lists']= 'bookmarkuserscontroller/lists';
$route['faq']= 'bookmarkuserscontroller/faq';
$route['reviews']= 'bookmarkuserscontroller/reviews';
$route['add-review']= 'bookmarkuserscontroller/add_review';




//User
$route['users'] = 'usercontroller/index';
$route['profile'] = 'profilecontroller';
$route['update-profile'] = 'profilecontroller/update_profile';
$route['contact-submit'] = 'usercontroller/contact_form_submit';
$route['reviews-submit'] = 'usercontroller/reviews_form_submit';
$route['catalogue-filter'] = 'usercontroller/catalogue_filter';
$route['close-account'] = 'usercontroller/close_account';




//Login
$route['forgot-password'] = 'index.php/logincontroller/forgot_password';
$route['change-password/(:any)'] = 'index.php/logincontroller/change_password/$1';
$route['login'] = 'logincontroller';
$route['email-verify'] = 'logincontroller/email_verify';
$route['logout'] = 'logoutcontroller';
//$route['register'] = 'logincontroller/register';
$route['privacy-policy'] = 'logincontroller/privacyPolicy';
$route['term-of-use'] = 'logincontroller/termOfUse';


//Admin
//$route['admin'] = 'admincontroller/index';
$route['admin'] = 'bannercontroller/company'; 
$route['admin/profile-setting'] = 'admincontroller/prf_set';
$route['admin/users'] = 'admincontroller/users';
$route['admin/user/tabs'] = 'admincontroller/usertabs';

$route['admin/assign-user'] = 'assignusercontroller/index';
$route['admin/assign-team'] = 'assignusercontroller/team';
$route['admin/update-asign'] = 'assignusercontroller/update_assign';
$route['admin/team-assign'] = 'assignusercontroller/assignteam';
$route['admin/team-assign-to-user'] = 'assignusercontroller/assignteamtousers';

$route['admin/companies'] = 'bannercontroller/company';
//$route['admin/tabs/bookmarks'] = 'bookmarkcontroller/Bookmarksbytab';
$route['admin/tabs/bookmarks'] = 'bookmarkcontroller/add';


$route['admin/teams'] = 'teamcontroller/index';
$route['admin/bookmarks'] = 'bookmarkcontroller/index';


$route['admin/subtabs'] = 'subtabscontroller/index';


$route['cloning-data'] = "commoncontroller/clonedata";



//Catalogue
$route['admin/catalogue'] = 'cataloguecontroller/index';
$route['admin/catagory'] = 'cataloguecontroller/catagory';
$route['admin/brands'] = 'cataloguecontroller/brands';


//common delete function
$route['delete-data'] = 'commoncontroller/delete_row';

//Contacts
$route['admin/contact'] = 'contactcontroller/index';
$route['admin/contact-view'] = 'contactcontroller/contact_view';
$route['admin/contact-edit'] = 'contactcontroller/contact_edit';
$route['admin/contact-delete'] = 'contactcontroller/contact_delete';


//Reviews
$route['admin/reviews'] = 'reviewscontroller/index';

//Faqs
$route['admin/faqs'] = 'faqscontroller/index';
$route['admin/requests'] = 'requestcontroller/index';



//Settings
$route['admin/setting'] = 'settingscontroller/index';

//Graphics
$route['admin/graphics'] = 'graphicscontroller/index/';
$route['admin/graphics/:num'] = 'graphicscontroller/index/$1';
//$route['admin/graphics-save'] = 'graphicscontroller/save';



//Bookmark Ajax
$route['bookmarks-tabs'] = 'bookmarkuserscontroller/bookmark_ajax';

$route['manage-subtab'] ='bookmarkuserscontroller/manage_subtabs';

$route['tab/manage_tabs'] = 'tabcontroller/managetabs';

// Site Multilanguage
$route['loadlanguage/(:any)'] = "Loader/jsFile/$1";

$route['auth/(.+)'] = 'logincontroller/auth/$1';

$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;



