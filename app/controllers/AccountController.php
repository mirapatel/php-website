<?php 

use Intervention\Image\ImageManager;

class AccountController extends PageController {

	private $acceptableImageTypes = ['image/jpeg', 'image/png', 'image/gif','image/bmp' ,'image/tiff'];

	public function __construct($dbc) {

		//run the parent constructor
		parent::__construct();

		$this->dbc = $dbc;

		$this->mustBeLoggedIn();

		//did the user submit new contact details
		if (isset($_POST['update-contact'])) {
			$this->processNewContactDetails();
		}

		//did the user submit the new posst form
		if(isset($_POST['new-post'])) {
			$this->processNewPost();
		}
	}

	public function buildHTML() {

		echo $this->plates->render('account', $this->data);
	}

	private function processNewContactDetails() {
		// die('ready');

		//validation
		$totalErrors = 0;

		//validate the first name
		if (strlen($_POST['first-name']) > 50) {
			$this->data['firstNameMessage'] = 'First name must be at most 50 characters';
			$totalErrors ++;
		} 

		if (strlen($_POST['last-name']) > 50) {
			$this->data['lastNameMessage'] = 'Last name must be at most 50 characters';
			$totalErrors ++;
		} 

		//if total errors is still 0 
		if( $totalErrors == 0) {
			//form passed!
			//time to update the database
			$firstName = $this->dbc->real_escape_string($_POST['first-name']);
			$lastName = $this->dbc->real_escape_string($_POST['last-name']);

			$userID = $_SESSION['id'];

			//update the SQL
			$sql = "UPDATE users
					SET first_name = '$firstName',
						last_name = '$lastName'  
					WHERE id = $userID ";

			//run the query
			$this->dbc->query($sql);
		}

	} //end of contact details

	private function processNewPost() {

		//count errors 
		$totalErrors = 0;

		$title = trim ($_POST['title']);
		$desc = trim ($_POST['desc']);



		//title
		if (strlen($title) == 0) {
			$this->data['titleMessage'] = 'Required ';
			$totalErrors++;
		} elseif (strlen($title) > 100) {
			$this->data['titleMessage'] = 'Cannot be more than 100 characters';
			$totalErrors++;
		}

		//description
		if (strlen($desc) == 0) {
			$this->data['descMessage'] = 'Required ';
			$totalErrors++;
		} elseif (strlen($desc) > 1000) {
			$this->data['descMessage'] = 'Cannot be more than 1000 characters';
			$totalErrors++;
		}

		//make sure the user has provided an image //if statements run with a true never a false
		if (in_array($_FILES['image'] ['error'], [1,3,4] )) {
			//show error messages to the user
			$this->data['fileMessage'] = 'Image failed to upload';
			$totalErrors++;
		} elseif (!in_array($_FILES['image']['type'], $this->acceptableImageTypes)) {
			$this->data['fileMessage'] = 'Must be jpeg, png, gif, bmp or tiff';
			$totalErrors++;

		}

		if($totalErrors == 0) {

			//instance of intervention image
			$manager = new ImageManager();

			//get the file that we just uploaded
			$image = $manager->make($_FILES['image']['tmp_name']);

			//determine the file type
			$fileExtension = $this->getFileExtension ($image->mime());

			$fileName = uniqid();

			$image->save("img/uploads/original/{$fileName}{$fileExtension}");

			$image->resize(320, null, function ($constraint) {
    		$constraint->aspectRatio();
			});

			$image->save("img/uploads/stream/$fileName$fileExtension");

			//filter the data
			$title = $this->dbc->real_escape_string($title);

			$desc = $this->dbc->real_escape_string($desc);

			//get id of logged in user

			$userID = $_SESSION['id'];

			//SQL
			$sql = "INSERT INTO posts (title, description, user_id, image)
					VALUES ('$title', '$desc', $userID, '$fileName$fileExtension') ";

			$this->dbc->query($sql);

			//make sure it worked
			if ($this->dbc->affected_rows) {
				$this->data['postMessage'] = 'Success!';
			} else {
				$this->data['postMessage'] = 'Opps! Something went wrong!';
			}

			//success message (or error message)
		}


	}	

	private function getFileExtension($mimeType) {

		switch($mimeType) {

			case 'image/png':
				return '.png';
			break;

			case 'image/gif':
				return '.gif';
			break;

			case 'image/jpeg':
				return '.jpg';
			break;

			case 'image/bmp':
				return '.bmp';
			break;

			case 'image/tiff':
				return '.tiff';
			break;
		}
	}

}