<?php

session_start();

// phpinfo();

//make everything in the vendor folder available to use
require 'vendor/autoload.php';
require 'app/controllers/PageController.php';

// echo $plates->render('landing');

//load appropriate page

//has the user requested a page?
// if (isset($_GET['page'])) {
// 	//requested page
// 	$page = $_GET['page'];

// } else {
// 	//home page
// 	$page = 'landing';
// }

$page = isset($_GET['page']) ? $_GET['page'] : 'landing';

//connect to the database
$dbc = new mysqli('localhost', 'root', '', 'pinterest_clone');;

//load appropriate files based on the page
switch($page) {

	case 'landing':
	case 'register':
		require 'app/controllers/LandingController.php';
		$controller = new LandingController($dbc);
	break;

	case 'about':
		echo $plates->render('about');
	break;

	case 'contact':
		echo $plates->render('contact');
	break;

	case 'login':
		require 'app/controllers/LoginController.php';
		$controller = new LoginController($dbc);
	break;

	case 'stream':
	require 'app/controllers/StreamController.php';
		$controller = new StreamController($dbc);
	break;

	case 'account':
	require 'app/controllers/AccountController.php';
		$controller = new AccountController($dbc);
	break;

	case 'post':
	require 'app/controllers/PostController.php';
		$controller = new PostController($dbc);
	break;

	case 'edit-comment':
	require 'app/controllers/EditCommentController.php';
		$controller = new EditCommentController($dbc);
	break;

	case 'edit-post':
	require 'app/controllers/EditPostController.php';
		$controller = new EditPostController($dbc);
	break;


	default:
		echo $plates->render('error404');
	break;

}

$controller->buildHTML();
