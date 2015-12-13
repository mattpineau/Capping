<?php 
    session_start();
?>

<html>
<head>
  <title>Marist College | My Transfer Reports</title>
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
                    
                        <a class="pull-right" href="userHome.php">Home</a>
                        <a class="pull-right" href="logout.php"> Log Out</a>
                    </li>
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

       <center><h3></h3>
      
    
    
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <div class="container">
  <h2>My Transfer Reports</h2>         
  <table class="table table-hover">
    <thead>
      <tr>
        <th>Report Name</th>
      </tr>
    </thead>
    <tbody>
        <?php 
            $dbconn = pg_connect("host=localhost dbname=AtlasDB user=postgres password=Globe123") or die('Could not connect: ' . pg_last_error()); 
            // Query to get all of the user's transfer reports
            $result = pg_query_params($dbconn, 'SELECT reportName, transferReportId FROM TransferReports WHERE studentId = $1', array($_SESSION['currentUserId']));
            // For every row, print a table row with the name
            while ($row = pg_fetch_row($result)) {
              echo '<tr style = "cursor: pointer" onclick = "location.href = \'transferReportView.php?transferReportId=' . $row[1] . '\'"><td>' . $row[0] . '</td></tr>';
            }
            pg_free_result($result);
        ?>
    </tbody>
  </table>
</div>

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