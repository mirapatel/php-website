<?php

class EditCommentController extends PageController {

	public function __construct($dbc) {

		//run the parent constructor
		parent::__construct();

		$this->dbc = $dbc;

		$this->mustBeLoggedIn();
		$this->mustOwnComment();

		//did the user submit the form
		if(isset($_POST['edit-comment'])) {
			$this->processComment();
		}

	}

	public function buildHTML() {

		echo $this->plates->render('edit-comment', $this->data);

	}



	private function mustOwnComment() {

		//get the logged in user ID 
		$userID = $_SESSION['id'];

		//get the comment id
		$commentID = $this->dbc->real_escape_string($_GET['id']);

		//get the comment details
		$sql = "SELECT comment, post_id
				FROM comments
				WHERE id = $commentID
				AND user_id = $userID";

		//run the query and capture the result
		$result = $this->dbc->query($sql);

		//if there isnt a result
		if ( !$result || $result->num_rows == 0) {
			//redirect the user
			header('Location: index.php?page=stream');
		} else {
			$theComment = $result->fetch_assoc();

			$this->data['comment'] = $theComment['comment'];
			$this->data['post_id'] = $theComment['post_id'];
		}

	}

	private function processComment() {

		$totalErrors=0;

		//check the length of the comment

		if (strlen($_POST['comment']) > 1000){
			$totalErrors++;
			$this->data['commentError'] = "Must be less than 1000 characters";
		}

		//check the errors, if all is good update the database

		if($totalErrors == 0) {
			
			//get the comment ID
			$commentID = $_GET['id'];

			//get the comment
			$comment = $this->dbc->real_escape_string($_POST['comment']);

			//prepare SQL

			//$comment in single quotes because it is a string
			$sql = "UPDATE comments
					SET comment = '$comment', 
						updated_at = CURRENT_TIMESTAMP 
					WHERE id = $commentID"; 

			$this->dbc->query($sql);

			//redirect the user back to the post
			header('Location: index.php?page=post&postid='.$this->data['post_id']);


		}




	}


}