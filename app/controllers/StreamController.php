<?php

class StreamController extends PageController {

	//properties
	private $top5Favourties;


	//constructor

	public function __construct($dbc) {

		//run the parent constructor
		parent::__construct();

		$this->dbc = $dbc;

		//if you are not logged in
		if( !isset($_SESSION['id']) ) {
			//redirect user to the login page
			Header('Location: index.php?page=login');
		}
	}
	//methods
	public function buildHTML() {

		//get the latest posts (pins)
		$allData = $this->getLastestPosts();

		$data = [];

		$data['allPosts'] = $allData;

		echo $this->plates->render('stream', $data);

	}

	private function getLastestPosts() {

		//prepare some SQL
		$sql = "SELECT *
				FROM posts";

		//run the SQL and capture the result
		$result = $this->dbc->query($sql);

		//extract the results as an array
		$allData = $result->fetch_all(MYSQL_ASSOC);

		// echo '<pre>';
		// print_r($allData);
		// die();

		//return the results to the code that called this function
		return $allData;


	}
}