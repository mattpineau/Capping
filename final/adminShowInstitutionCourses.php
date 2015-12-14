<?php
    session_start();
    if (!isset($_SESSION['currentUserId']) || !isset($_SESSION['currentFirstName'])) {
      header('Location: index.html');
      exit();
    }
?>

<html>
<head>
  <title>Admin | Institution Courses</title>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="Atlas!">

<!-- Stylesheets -->
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.4/themes/smoothness/jquery-ui.css">
<link rel="stylesheet" href="css/logo-nav.css">
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
                        <a class="pull-right" href="adminHome.php">Home</a>
                    </li>
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>
    <center>
  <h1>Current DCC Courses </h1>
  <?php

    $dbconn = pg_connect("host=localhost dbname=AtlasDB user=postgres password=Globe123") or die('Could not connect: ' . pg_last_error());

    $stat = pg_connection_status($dbconn);
  if ($stat === PGSQL_CONNECTION_OK) {
      // echo 'Connection status ok';
  } else {
      echo 'Connection status bad';
  }    

  
    $query = 'SELECT coursetitle, coursenum FROM institutioncourses ORDER BY coursenum';
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());

    $i = 0;

    echo '<html><body><table class = "table table-hover"><thead><tr><td><b>Course Title</b></td><td><b>Course Number</b></td></tr></thead><tbody>';

while ($row = pg_fetch_row($result)) 
{
  echo '<tr>';
  $count = count($row);
  $y = 0;
  while ($y < $count)
  {
    $c_row = current($row);
    echo '<td>' . $c_row . '</td>';
    next($row);
    $y = $y + 1;
  }
  echo '</tr>';
  $i = $i + 1;
}
echo '</tbody>';
pg_free_result($result);

pg_close($dbconnection);

?>

</center>
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
  


