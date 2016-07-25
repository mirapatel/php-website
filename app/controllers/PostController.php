<?php

class PostController extends PageController {

	public function __construct($dbc) {

		//run the parent constructor
		parent::__construct();

		$this->dbc = $dbc;

		//did the user add a comment
		if(isset($_POST['new-comment'])) {
			$this->processNewComment();
		}

		$this->getPostData();

	}

	public function buildHTML() {

		echo $this->plates->render('post', $this->data);
	}

	private function getPostData() {

		//filter the ID
		$postID = $this->dbc->real_escape_string($_GET['postid']);

		//get info about this post
		$sql = "SELECT title, description, image, created_at, updated_at, first_name, last_name, user_id
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

			//if the user does not have a name
			if (!$fName && !$lName ) {
				//anon
				$this->data['post']['first_name'] = 'Anon';
			}

		}

		//get all the comments!
		$sql = "SELECT comments.id, user_id, comment, CONCAT(first_name, ' ' ,last_name) AS author
				FROM comments
				JOIN users
				ON comments.user_id = users.id
				WHERE post_id = $postID 
				ORDER BY created_at DESC ";

		$result = $this->dbc->query($sql);

		//extract the data as an associative array
		$this->data['allComments'] = $result->fetch_all(MYSQLI_ASSOC); //come back as an associate array not a numeric array

		// echo '<pre>';
		// print_r($allComments);
		// die();

	} //endof getPostData

		private function processNewComment() {
		
			//validate the comment
			$totalErrors = 0;



			//minimum length

			//maximum  (1000)

			//if passed, add to the database

			if ($totalErrors == 0) {

				//filter the comment
				$comment = $this->dbc->real_escape_string($_POST['comment']);

				$userID = $_SESSION['id'];

				$postID = $this->dbc->real_escape_string($_GET['postid']);

				//prepare SQL
				$sql = "INSERT INTO comments (comment,user_id, post_id)
						VALUES ('$comment', $userID, $postID)
						";

				//run the SQL
				$this->dbc->query($sql);

				//make sure the query worked

			}


	}
	
}