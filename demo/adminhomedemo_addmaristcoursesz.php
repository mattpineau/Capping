<html>
<head>
	<title> Added Course </title>
</head>

<body>
	
	<?php
	 
	$dbconn = pg_connect("host=localhost dbname=AtlasDB user=postgres password=Globe123") or die('Could not connect: ' . pg_last_error()); 

		$maristCourseTitle = pg_escape_string($_POST['maristCourseTitle']); 
        $maristCourseNum = pg_escape_string($_POST['maristCourseNum']); 
        $maristCourseSubject = pg_escape_string($_POST['maristCourseSubject']); 

        $query = "INSERT INTO maristcourses(maristCourseTitle, maristCourseNum, maristCourseSubject) VALUES('" . $maristCourseTitle . "', '" . $maristCourseNum . "', '" . $maristCourseSubject . "')"; 
        $result = pg_query($query); 
        if (!$result) { 
            $errormessage = pg_last_error(); 
            echo "Error with query: " . $errormessage; 
            exit(); 
        } 
        printf ("These values were inserted into the database - %s %s %s", $maristCourseTitle, $maristCourseNum, $maristCourseSubject); 
        pg_close(); 
        ?> 

</body>
</html>