<?php

class StreamController extends PageController {

	//properties
	private $top5Favourties;


	//constructor

	public function __construct($dbc) {
		$this->dbc = $dbc;

		//if you are not logged in
		if( !isset($_SESSION['id']) ) {
			//redirect user to the login page
			Header('Location: index.php?page=login');
		}
	}
	//methods
	public function buildHTML() {

	}
}