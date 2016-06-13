<?php

//make everything in the vendor folder available to use
require 'vendor/autoload.php';


//instantiate (create instance of) plates library
$plates = new League\Plates\Engine('app/templates');

// echo $plates->render('landing');

//load appropriate page

//has the user requested a page?
if (isset($_GET['page'])) {
	//requested page
	$page = $_GET['page'];

} else {
	//home page
	$page = 'landing';
}

//load appropriate files based on the page
switch($page) {

	case 'landing':
	case 'register':
		echo $plates->render('landing');
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
