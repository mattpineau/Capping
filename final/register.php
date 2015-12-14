<?php 
    $errors = array(); // Error array
    
    // Check for all fields to have something entered
    if (empty($_POST['email'])){
    	array_push($errors, "<center><div class='alert alert-danger fade in' role='alert'><a href='#' class='close' data-dismiss='alert'>&times;</a>Sorry, but the Email field is empty");
    }
    if (empty($_POST['fname'])){
    	array_push($errors, "First Name field empty");
    }
    if (empty($_POST['lname'])){
    	array_push($errors, "Last Name field empty");
    }
    if (empty($_POST['password'])){
    	array_push($errors, "Password field empty");
    }
    if (empty($_POST['confirm-password'])){
    	array_push($errors, "Confirm Password field empty</div>");
    }
    if ($_POST['password'] != $_POST['confirm-password']) {
    	array_push($errors, "<center><div class='alert alert-danger fade in' role='alert'>Password and confirmation do not match</div></div>");
    }
    if (!empty($errors)) {
    	// Print errors and return to registration...
    	foreach($errors as &$error) {
    		echo $error . "<br>";
    	}
        unset($error);
        include('index.html');
        //make register tab show by default
echo "<script>
        $(function () {
            $('#register-form').delay(100).fadeIn(100);
            $('#login-form').fadeOut(100);
            $('#login-form-link').removeClass('active');
            $('#register-form-link').addClass('active');
            e.preventDefault();
        

    });
        </script>";
        exit;

    }
    else {
    // Connect to db
    $dbconn = pg_connect("host=localhost dbname=AtlasDB user=postgres password=Globe123") or die('Could not connect: ' . pg_last_error());

    // Escape strings
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $password = $_POST['password'];
    $confirmPassword = $_POST['confirm-password'];
    
    $result = pg_query_params($dbconn, 'SELECT * FROM Users WHERE email = $1', array($email));
    if (pg_num_rows($result) != 0) {
    	array_push($errors, "<center><div class='alert alert-danger' role='alert'>This email is already registered.</div>");
    	pg_free_result($result);
    	// Print errors and return to registration...
    	foreach($errors as &$error) {
    		echo $error . "<br>";
    	}
    	unset($error);
    	include('index.html');
    	exit;
    }

    else {
    	// free result
        pg_free_result($result);

    	// Hash Password
    	$password = hash('sha256', $password);
        
        // Insert into users
        $result1 = pg_query_params($dbconn, 'INSERT INTO Users(userfirstname, userlastname, email, password) VALUES ($1, $2, $3, $4)', array($fname, $lname, $email, $password));

        pg_free_result($result1); //Free result

        $result2 = pg_query_params($dbconn, 'SELECT userId FROM Users WHERE email = $1 AND password = $2', array($email, $password)); //Query for the new user id

        $row = pg_fetch_array($result2);

        $newUserId = $row[0]; // Get the user Id

        pg_free_result($result2); //Free the result

        // Insert into students
        $result3 = pg_query_params($dbconn, 'INSERT INTO Students(studentId, studentInstitutionId, dateOfBirth) VALUES ($1, $2, $3)', array($newUserId, 1, '1994-04-20'));
        

        if (!$result3) {
    	    $errormessage = pg_last_error();
    	    echo "Error with query: " . $errormessage;
    	    exit;
        }

        pg_free_result($result3);// Free the result
        echo ("<center><div class='alert alert-success fade in' role='alert'>Registration Success! You may now log in.</div>");
        include('index.html');
        exit();
    }

  
    }


?>
