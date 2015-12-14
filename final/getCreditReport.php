
<?php 
    session_start();

    if (!isset($_SESSION['currentUserId']) || !isset($_SESSION['currentFirstName'])) {
      header('Location: index.html');
      exit();
    } 

?>

<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Atlas!">

<!-- Stylesheets -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<link href="css/logo-nav.css" rel="stylesheet">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="css/myStyleSheet.css">

</head>
<body>

 <!-- Navigation -->
    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container">
            <!-- Brand and toggle get grouped for better mobile display -->
            <div class="navbar-header">
                <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="index.html">
                    <img src="http://www.marist.edu/images/logo.png" alt="">
                </a>
            </div>
            <!-- Collect the nav links, forms, and other content for toggling -->
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                <ul class="nav navbar-nav">
                    <li>
                        <a href="https://www.marist.edu/about/">About</a>
                    </li>
                    <li>
                        <a href="http://www.marist.edu/admission/freshman/faq.html">FAQ</a>
                    </li>
                    <li>
                        <a href="https://www.marist.edu/contact/">Contact</a>
                    </li>
                 </ul>
                 <ul class="nav navbar-nav navbar-right">
                    <li>
                        <a class="pull-right" href="logout.php"> Log Out</a>
                        <a class="pull-right" href="userHome.php">Home</a>
                    </li>
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

       <center> <h3>All Done!</h3>
      
    
    <!--<h4>When you are done entering your courses below, click on finish.</h4></center>-->
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <div class="container">
        
        
        <?php

    $dbconn = pg_connect("host=localhost dbname=AtlasDB user=postgres password=Globe123") or die('Could not connect: ' . pg_last_error()); 
    
    // If bad connection echo
    $stat = pg_connection_status($dbconn);
    if ($stat !== PGSQL_CONNECTION_OK) {
      echo 'Connection status bad';
    }

    // Create an array to store all $POST variables
    $courses = array();
    $years = array();
    $terms = array();
    
    // There can only be a max of 50 rows added
    // We start at 1 because of the hidden select box on our page that we want to skip
    for ($i = 1; $i < 51; $i++) {

        // If this POST variable is set
        if (isset($_POST['courseDropdown' . $i])) {
            
            // If the user enters the same class twice, we only want to count it once
            $isDuplicate = FALSE;

            // So for all the previous courses we've pushed to the array
            for ($j = 0; $j < count($courses); $j++){
                // If it equals what is currently being evaluated, set $isDuplicate to true
                if ($courses[$j] == $_POST['courseDropdown' .$i]) {
                    $isDuplicate = TRUE;
                    break;
                }
            }
            // As long as something was entered in each and it's not a duplicate..
            if ($_POST['courseDropdown' . $i] != "" && $_POST['yearDropdown' . $i] != "" && $_POST['termDropdown' . $i] != "" && !$isDuplicate) {

                // Push them to their respective arrays
                array_push($courses, $_POST['courseDropdown'. $i]); // i - 1 because i starts at 1 and we want our arrays to begin at 0
                array_push($years, $_POST['yearDropdown'. $i]);
                array_push($terms, $_POST['termDropdown'. $i]);
            
        }
            // Otherwise just skip this iteration
            else {
                continue;
            }
        }
        // Otherwise break out of loop
        else {
            break;
        }
    }
    // Echo our table and first row
    echo "<table class = 'table table-hover'><thead><tr><th>DCC Course</th><th>Marist Equivalent</th><th>Credit Hours</th></tr></thead><tbody>";

        // For as many rows as the user submits
        for ($i = 0; $i < count($courses); $i++) {
            // Execute the query
            $result = pg_query_params($dbconn, "SELECT InstitutionCourses.courseTitle, MaristCourses.maristCourseTitle, MaristCourses.numCredits FROM InstitutionCourses INNER JOIN TransfersTo ON InstitutionCourses.courseId = TransfersTo.courseId INNER JOIN MaristCourses ON MaristCourses.maristCourseId = TransfersTo.maristCourseId WHERE InstitutionCourses.courseId = $1", array($courses[$i]));
           
            // Store the number of rows
            $numRows = pg_num_rows($result);
             
             // If no rows were found, the course didn't match
            if ($numRows == 0) {
                // Free the result
                pg_free_result($result);
                // We'll want to still display the course in the report though, so run a new query to get its title
                $result = pg_query_params($dbconn, "SELECT courseTitle FROM InstitutionCourses WHERE courseId = $1", array($courses[$i]));
                $row = pg_fetch_array($result);

                echo "<tr><td>" . $row[0] . "</td><td class = 'bg-danger'>None</td><td> 0 </td></tr>";
            }

            // For now, if the course matches multiple transfer possibilities, we will just count it for the first row returned
            // Better functionality depending on the client's preference could be implemented in the future
            else {
                $row = pg_fetch_array($result);
                // Echo the table row and appropriate data
                echo "<tr><td>" . $row[0] . "</td><td>" . $row[1] . "</td><td>" . $row[2] . "</td></tr>";
            }
        }
    // Save the classes taken as a session variable
        $_SESSION['coursesTaken'] = $courses;
        $_SESSION['yearsTaken'] = $years;
        $_SESSION['termsTaken'] = $terms;
    pg_free_result($result);
    pg_close($dbconn);

    ?>
            </tbody>
        </table>
        <form action = "saveReport.php" method = "post">            
            <input type= "submit" value = "Save Report" class="btn btn-success pull-right">
            <input type="text" placeholder="Name Your Report" id = "reportName" name="reportName" class="pull-right">
        </form>
    </div>

    
    <footer class="footer">
      <div class="container">
        <p class="text-muted">© 2015 Marist College</p>
      </div>
    </footer>
</body>

<!-- JavaScript Files  -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
<script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/jquery-ui.min.js"></script>
<script src="js/jquery.js"></script>
<script src="js/myScripts.js"></script>
</html>