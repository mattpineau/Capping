// Code to check login credentials and create a session if the
// username and password match in the database.

<?php

$error = ''; // Create a variable to hold errors

// If the submit button was hit
if (isset($_POST[ 'login-submit' ])) {
	// Check if username is empty and throw error if true
	if (empty($_POST[ 'username' ])) {
		$error = "The username box was left empty.  Please enter a valid username.";
	}
    // Check if the password is empty and throw an error if true
	else if (empty($_POST[ 'password' ])) {
		$error = "The password box was left empty.  Please enter the valid password."
	}
	// Otherwise store the username and password entered as variables
	else {
		$username = $_POST[ 'username' ]; // Define username variable
		$password = $_POST[ 'password' ]; // Define password variable

        // Create a connection to the database
	    $dbconn = pg_connect("host=localhost dbname=AtlasDB user=postgres password=Globe123")
        or die('Could not connect: ' . pg_last_error());

        // Query to check username and password combination against students
        $query1 = pg_query_params( $dbh, 'SELECT * FROM students WHERE username = $1 and password = $2', array($username, $password) );

        // Query to check username and password combination against admins
        $query2 = pg_query_params( $dbh, 'SELECT * FROM admins WHERE username = $1 and password = $2', array($username, $password) );

        // Store number of rows returned from each query
        $rows1 = pg_num_rows($query1); 
        $rows2 = pg_num_rows($query2);

        // If exactly one result is returned from the first query...
        if ($rows1 == 1) {
            session_start(); // Start the session
        }
        // Otherwise, if one result is returned from the second
        else if ($rows2 == 1) {
            session_start();
        }
        // Otherwise the username/password combination was not found
        else {
        	$error = "Username and/or password invalid."
        }




	}
}



