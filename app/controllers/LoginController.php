<?php

class LoginController extends PageController {

	public function __construct($dbc) {

		//make sure the PageControllers constructor still runs
		parent::__construct();

		//save the database connection
		$this->dbc = $dbc; 

		//if the login form has been submitted
		if(isset($_POST['login'])) {
			$this->processLoginForm();
		}
	}

	public function buildHTML() {

		echo $this->plates->render('login', $this->data);
	}


	private function processLoginForm() {
		$totalErrors = 0;

		//make sure the email address has been provided
		if(strlen($_POST['email']) < 6) {

			//prepare error message
			$this->data['emailMessage'] = 'Invalid Email';
			$totalErrors++;
		}

		//make sure the password is at least 8 characters

		if(strlen($_POST['password']) < 8) {

			$this->data['passwordMessage'] = 'Invalid Password';
			$totalErrors++;
		}

		//if there are no errors
		if ($totalErrors == 0) {

			//check the database for the email address
			//get the hashed password too
			$filteredEmail = $this->dbc->real_escape_string($_POST['email']);

			//prepare sql
			$sql = "SELECT id, password
					FROM users
					WHERE email = '$filteredEmail' ";


			//run the query
			$result = $this->dbc->query($sql);

			//is there a result
			if ($result->num_rows == 1) {

				//check the password
				$userData = $result->fetch_assoc();

				//check the password
				$passwordResult = password_verify($_POST['password'], $userData['password']);

				//if the result was good
				if ($passwordResult == true ) {
					//log the user in
					$_SESSION['id'] = $userData['id'];
					header('Location: index.php?page=stream');
				} else {
					//prepare error message
					$this->data['loginMessage'] = 'Email or Password is incorrect';
				}

				// echo '<pre>';
				// print_r($userData);
				// die();

			} else {

				//credentials do not match our records
				$this->data['loginMessage'] = 'Email or Password is incorrect';
			}



		}
	}
}