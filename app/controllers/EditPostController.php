<?php 

class EditPostController extends PageController {

	public function __construct($dbc) {

		//run the parent constructor
		parent::__construct();

		$this->dbc = $dbc;

		$this->mustBeLoggedIn();

		//did the user submit the edit form?
		if (isset($_POST['edit-post'])) {
			$this->processPostEdit();
		}

		//get info about the post
		$this->getPostInfo();

	}

	public function buildHTML() {

		echo $this->plates->render('edit-post', $this->data);
	}

	private function getPostInfo() {

		//get the POST ID fron the GET array
		$postID = $this->dbc->real_escape_string($_GET['id']);

		//get the user id
		$userID = $_SESSION['id'];

		//prepare the query
		$sql = "SELECT title, description, image
				FROM posts
				WHERE id = $postID
				AND user_id = $userID";

		//run the query
		$result = $this->dbc->query($sql);

		//if the query failed
		if (!$result || $result->num_rows == 0) {
			//didnt own the post, post was deleted or they didnt own the post
			header("Location: index.php?page=post&postid=$postID");

		} else {
	

			
			//wait, what if the user has submitted the form? we dont want to loose their changes
			if (isset($_POST['edit-post'])) {
				//use the form data
				$this->data['post'] = $_POST;

				//use the original title
				$result = $result->fetch_assoc();
				$this->data['originalTitle'] = $result['title'];
			} else {
				//use the database data
				$result = $result->fetch_assoc();
				$this->data['post'] = $result;
				$this->data['originalTitle'] = $result['title'];
			}
		

		}

	}	

	private function processPostEdit() {
		
		$totalErrors = 0;
		$title = $_POST['title'];
		$desc = $_POST['description'];

		//validation
		if (strlen($title) > 100) {
			$totalErrors++;
			$this->data['titleError'] = "Can only be 100 characters";
		}

		if (strlen($desc) > 1000) {
			$totalErrors++;
			$this->data['descError'] = "Can only be 1000 characters";
		}

		//if there are no errors
		if ($totalErrors == 0 ) {

			//filter the data
			$safeTitle = $this->dbc->real_escape_string($title);
			$safeDesc = $this->dbc->real_escape_string($desc);
			$postID = $this->dbc->real_escape_string($_GET['id']);
			$userID = $_SESSION['id'];


			//prepare the sql
			$sql = "UPDATE posts
					SET title = '$safeTitle',
						description = '$safeDesc'
					WHERE id = $postID
					AND user_id = $userID";

			$result = $this->dbc->query($sql);

			//redirect the user to the post page
			header("Location: index.php?page=post&postid=$postID");
		} 
	}





}