<html>
<head>
	<title> Removed Course </title>
</head>

<body>
	<?php

	$dbconn = pg_connect("host=localhost dbname=AtlasDB user=postgres password=Globe123") or die('Could not connect: ' . pg_last_error()); 

	    $courseToBeRemoved = pg_escape_string($_POST['$selcname']);
        echo $courseToBeRemoved;
            
    $query = "DELETE FROM maristcourses where maristcoursetitle='$courseToBeRemoved'";
    $result = pg_query($query);
    if (!$result) {
    	$errormessage = pg_last_error();
    	echo "Error with query: " . $errormessage;
    	exit();
    }
    printf ("These values were removed from the database: %s", $courseToBeRemoved);
    pg_free_result($result);
    pg_close();

    ?>
 
</body>
</html>