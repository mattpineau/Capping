<?php
    session_start();

    if (!isset($_SESSION['currentUserId']) || !isset($_SESSION['currentFirstName'])) {
      header('Location: index.html');
      exit();
    }
    // Unset the coursesTaken session var if going home
    if (isset($_SESSION['coursesTaken'])) {
        unset($_SESSION['coursesTaken']);
        unset($_SESSION['yearsTaken']);
        unset($_SESSION['termsTaken']);
    }
?>

<html>

<head>
  <title>Student | Home</title>
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
                        <a class="pull-right" href="userHome.php">Home</a>
                    </li>
                    
                </ul>
            </div>
            <!-- /.navbar-collapse -->
        </div>
        <!-- /.container -->
    </nav>

       <center><?php echo "<h3>Welcome, " . $_SESSION['currentFirstName'] . ". What would you like to do?</h3>"; ?>

       	

       	<form action="/final/classes.php" style="display: block;">

       		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">

       	<input type="submit" name="admin-submit-addcourses" id="admin-submit-addcourses" tabindex="1" class="form-control btn btn-default" value="Create Transfer Report">

       </div>
   </form>

       

      <form action="/final/studentMyTransferReports.php" style="display: block;">

       		<div class="row">
			<div class="col-sm-6 col-sm-offset-3">

       	<input type="submit"  name="admin-submit-editcourses" id="admin-submit-editcourses" tabindex="2" class="form-control btn btn-default" value="My Transfer Reports">

       </div>

	</form>       

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
