<?php

//make everything in the vendor folder available to use
require 'vendor/autoload.php';

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

//load appropriate files based on the page
switch($page) {

	case 'landing':
	case 'register':
		require 'app/controllers/LandingController.php';
		$controller = new LandingController();
	break;

	case 'about':
		echo $plates->render('about');
	break;

	case 'contact':
		echo $plates->render('contact');
	break;

	case 'login':
		echo $plates->render('login');
	break;

	case 'stream':
		echo $plates->render('stream');
	break;

	default:
		echo $plates->render('error404');
	break;

}

$controller->buildHTML();
