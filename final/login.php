<?php
    // Start a sesh!
    session_start();
    
    // Create an error variable
    $error = '';

    if (isset($_POST['login-submit'])) {
	// Check if email is empty and throw error if true
	    if (empty($_POST[ 'email' ])) {
		    $error = "<center><div class='alert alert-danger fade in' role='alert'> The email box was left empty.  Please enter a valid email.<br></div>";
            echo $error; //Echo error to user
            include('index.html'); // Include the index page to try again
	    }
	    
        // Check if the password is empty and throw an error if true
	    else if (empty($_POST[ 'password' ])) {
		    $error = "<center><div class='alert alert-danger' role='alert'> The password box was left empty.  Please enter the valid password.<br></div>";
            echo $error; // Echo error to user
            include('index.html'); // Include the index page to try again
	    }
	    // Otherwise store the email and password entered as variables
	    else {
		    // Connect to db
            $dbconn = pg_connect("host=localhost dbname=AtlasDB user=postgres password=Globe123") or die('Could not connect: ' . pg_last_error());

		    $email = $_POST[ 'email' ]; // Define email variable
		    $password = $_POST[ 'password' ]; // Define password variable

            $password = hash('sha256', $password); // Store hashed

		    // Query to check email and password combination against students
            $queryResult1 = pg_query_params( $dbconn, 'SELECT users.userid, users.userfirstname FROM students INNER JOIN users ON students.studentid = users.userid WHERE users.email = $1 AND users.password = $2', array($email, $password) );
            // Query to check email and password combination against admins
            $queryResult2 = pg_query_params( $dbconn, 'SELECT users.userid, users.userfirstname FROM admins INNER JOIN users ON admins.adminid = users.userid WHERE users.email = $1 AND users.password = $2', array($email, $password) );

            // Store number of rows returned from each query
            $numRows1 = pg_num_rows($queryResult1); 
            $numRows2 = pg_num_rows($queryResult2);
            
            // Check if this is sign-in is for a user
            if ($numRows1 == 1) {
            	// if so get the current user's id and first name
            	$row = pg_fetch_array($queryResult1);
            	$userId = $row[0];
            	$userFirstName = $row[1];
                // and set that as the current user's session variables
                $_SESSION['currentUserId'] = $userId;
                $_SESSION['currentFirstName'] = $userFirstName;
            	// redirect to classes.php
            	header('Location: /final/userHome.php');
            	exit();
            }
            // Check if this is an admin sign-in
            else if ($numRows2 == 1) {
            	// if so get the current user's id and first name
                $row = pg_fetch_array($queryResult2);
                $userId = $row[0];
                $userFirstName = $row[1];
                
                // and set that as the current user's session variables
                $_SESSION['currentUserId'] = $userId;
                $_SESSION['currentFirstName'] = $userFirstName;
                
                header('Location: /final/adminHome.php');
                exit();
            }
            // Otherwise the email/password combo was WRONG
            else {
            	$error = "<center><div class='alert alert-danger' role='alert'> Sorry, that email / password combination was wrong!</div>";
            	// Destroy the session!!!
            	session_destroy();
            	
                echo $error; // Echo the error
                include('index.html'); // and include the index
            }


	    }
	}

	else {
	    echo "<br>doesn't work";
	}