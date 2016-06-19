<?php

class LandingController extends PageController {

	//properties(attributes)

	private $emailMessage;
	private $passwordMessage;

	//constructor
	//magic 
	public function __construct($dbc) {
		// die('Landing controller has been made');

		//if the user has submitted the user form
		// echo is good for strings
		// echo '<pre>';
		// print_r ($_POST);
		// echo '<pre>';

		//save the database connection for later
		$this->dbc = $dbc;

		if (isset($_POST['new-account'])) {
			$this->validateRegistrationForm();
		}


	}

	//methods(functions)

	public function registerAccount() {

		//validate the user's data

		//check the database to see if email is in use

		//check the strength of the password

		//register the account OR show the error message

		//if successful, log user in and redirect to their brand new stream page (account)
	}

	public function buildHTML () {

		//instantiate (create instance of) plates library
		$plates = new League\Plates\Engine('app/templates');

		//prepare container for data
		$data = [];

		//if there is an E-Mail error 
		if ($this->emailMessage != '') {
			$data['emailMessage'] = $this->emailMessage;
		}

		if ($this->passwordMessage != '') {
			$data['passwordMessage'] = $this->passwordMessage;
		}

		echo $plates->render('landing', $data);
	}

	private function validateRegistrationForm() {

		$totalErrors = 0;

		//make sure the email has been provided and is valid
		if ($_POST['email'] == '') {
			//email is invalid
			$this->emailMessage = 'Invalid E-Mail';
			$totalErrors ++;
		}

		//make sure the email is not in use 
		$filteredEmail = $this->dbc->real_escape_string($_POST['email']);

		$sql = "SELECT email 
				FROM users 
				WHERE email = '$filteredEmail' ";

		//run the query
		$result = $this->dbc->query($sql);

		//if the query failed OR there is a result 
		if (!$result || $result->num_rows > 0 ) {
			$this->emailMessage = 'Email in use';
			$totalErrors++;

		}		

		//if the password is less than 8 characters long
		if (strlen ($_POST['password'] ) < 8) {

			//password is too short
			$this->passwordMessage = 'Must be atleast 8 characters';
			$totalErrors ++;
		}

		//determine if this data is valid to go into the database

		if ($totalErrors == 0) {

			//validation passed!

			//fliter user data before using it in a query
			

			//hash the password
			$filteredPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);

			// die($filteredPassword);

			//prepare the SQL
			$sql = "INSERT INTO users (email, password) 
					VALUES ('$filteredEmail','$filteredPassword')"; 

			$this->dbc->query($sql);

			//check to make sure this worked

			//log the user in
			$_SESSION['id'] = $this->dbc->insert_id;
			
			//redirect to the user to their stream page
			header('Location: index.php?page=stream');
		}
	}






}