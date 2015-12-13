<?php
    session_start();
    if (!isset($_SESSION['coursesTaken'])) {
    	header('Location: userHome.php');
    	exit();
    }
    else {
    	

    	$dbconn = pg_connect("host=localhost dbname=AtlasDB user=postgres password=Globe123") or die('Could not connect: ' . pg_last_error());

    	$currentUser = $_SESSION['currentUserId'];
    	$coursesTaken = $_SESSION['coursesTaken'];
    	$yearsTaken = $_SESSION['yearsTaken'];
    	$termsTaken = $_SESSION['termsTaken'];
    	$reportName = pg_escape_string($_POST['reportName']);

       
        // Create our new transfer report
        $result = pg_query_params($dbconn, 'INSERT INTO TransferReports (studentId, reportName) VALUES ($1, $2);', array($currentUser, $reportName));
        
        // Check for error
        if (!$result) {
        	echo 'Error with query creating table';
        	exit();
        }
        // Free the result
        pg_free_result($result);

        // Query to store that transferReportId as a variable
        $result = pg_query_params($dbconn, 'SELECT transferReportId FROM TransferReports WHERE studentId = $1 AND reportName = $2', array($currentUser, $reportName));
        $row = pg_fetch_array($result);
        $newTransferReportId = $row[0];

        // Free the result
        pg_free_result($result);


        // Insert these values into courses taken and associate them with the new report id
        for ($i = 0; $i < count($coursesTaken); $i++) {
        	$result = pg_query_params($dbconn, 'INSERT INTO CoursesTaken (transferReportId, courseId, semesterTaken, yearTaken) VALUES ($1, $2, $3, $4);', array($newTransferReportId, $coursesTaken[$i], $termsTaken[$i], $yearsTaken[$i]));
        	
        	// If there's a query error
        	if (!$result) {
        		// echo error, unset sessions, free results, and exit
        		echo 'Error with the query adding into courses taken.';
        		
        		unset($_SESSION['coursesTaken']);
                unset($_SESSION['yearsTaken']);
                unset($_SESSION['termsTaken']);
                
                pg_free_result($result);
        		
        		exit();
        	}
        	// Free the result
        	pg_free_result($result);

        }
        // now unset the sesssion variables and redirect to userHome
        unset($_SESSION['coursesTaken']);
        unset($_SESSION['yearsTaken']);
        unset($_SESSION['termsTaken']);

        header('Location: userHome.php');
        exit();
        
    }
?>
