<?php

class PostController extends PageController {

	public function __construct($dbc) {

		//run the parent constructor
		parent::__construct();

		$this->dbc = $dbc;

		$this->getPostData();

	}

	public function buildHTML() {

		echo $this->plates->render('post', $this->data);
	}

	private function getPostData() {

		//filter the ID
		$postID = $this->dbc->real_escape_string($_GET['postid']);

		//get info about this post
		$sql = "SELECT title, description, image, created_at, updated_at, first_name, last_name
				FROM posts
				JOIN users
				ON user_id = users.id
				WHERE posts.id = $postID ";
		// die($sql);

		$result = $this->dbc->query($sql);

		//make sure the query failed
		if(!$result || $result->num_rows == 0) {
			//redirect user to 4040 page
			header('Location: index.php?page=404');
		} else {
			$this->data['post'] = $result->fetch_assoc();

			// echo '<pre>';
			// print_r ($this->data['post']);

			//if the user does not have a name
			$fName = $this->data['post']['first_name'];
			$lName = $this->data['post']['last_name'];

			if (!$fName && !$lName ) {
				//anon
				$this->data['post']['first_name'] = 'Anon';
			}

		}

	}	
}