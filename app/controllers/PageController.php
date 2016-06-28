<?php

abstract class PageController {

	protected $title;
	protected $metaDesc;
	protected $dbc;
	protected $plates;
	protected $data = [];

	public function __construct() {

		//instantiate (create instance of) plates library
		$this->plates = new League\Plates\Engine('app/templates');
	}

	//force children classes to have the buildHTML function
	abstract public function buildHTML();

	public function mustBeLoggedIn() {

		//if you are not logged in
		if( !isset($_SESSION['id']) ) {
			//redirect user to the login page
			Header('Location: index.php?page=login');
		}
	}
}