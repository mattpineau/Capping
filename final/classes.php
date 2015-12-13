<?php session_start() 

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

       <center> <h3>What courses would you like to transfer?</h3>
      
    
    <h4>When you are done entering your courses below, click on finish.</h4></center>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/jquery-ui.min.js"></script>

    <div class="container">
        <form action = "getCreditReport.php" method = "post">
        <div class="row clearfix">
            <div class="col-md-12 table-responsive">
                <table class="table table-bordered table-hover table-sortable tab_logic">
                    <thead>
                        <tr>
                            <th class="text-center">
                                Course Name
                            </th>
                            <th class="text-center">
                                Year
                            </th>
                            <th class="text-center">
                                Term
                            </th>
                            <th class="text-center" style="border-top: 1px solid #ffffff; border-right: 1px solid #ffffff;">
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                        <tr id='addr0' data-id="0" class='hidden'>

                            <td data-name="courseDropdown">
                                <select name="courseDropdown">
                                     <option value = "">Select Course</option>
                                      <?php

                                           $dbconn = pg_connect("host=localhost dbname=AtlasDB user=postgres password=Globe123")
                                          or die('Could not connect: ' . pg_last_error());

                                         $stat = pg_connection_status($dbconn);
                                          if ($stat === PGSQL_CONNECTION_OK) {
                                           echo 'Connection status ok';
                                            } else {
                                               echo 'Connection status bad';
                                               }
      
                                   $result = pg_query($dbconn, "SELECT courseId, courseTitle FROM InstitutionCourses");
                    while ($row = pg_fetch_row($result)) {
                    echo "<option value=".$row[0].">".$row[1]."</option>";}
                    pg_close($db);
                    header('Content-type: application/json');
                    echo json_encode($result);
                    ?>

                        
                        

                            <td data-name="yearDropdown">
                                <select name="yearDropdown">
                                     <option value = "">Select Year Taken</option>
                                     <option value = "2015">2015</option>
                                     <option value = "2014">2014</option>
                                     <option value = "2013">2013</option>
                                     <option value = "2012">2012</option>
                                     <option value = "2011">2011</option>
                                     <option value = "2010">2010</option>
                                     <option value = "2009">2009</option>
                                     <option value = "2008">2008</option>
                                </select>
                            </td>
                            
                            <td data-name="termDropdown">
                                <select name="termDropdown">
                                    <option value = "">Select Term</option>
                                    <option value = "Spring">Spring</option>
                                    <option value = "Fall">Fall</option>
                                    <option value = "Winter">Winter</option>
                                    <option value = "Summer">Summer</option>
                                </select>
                            </td>
                            <td data-name="del">
                                <button nam"del0" class='btn btn-danger glyphicon glyphicon-remove row-remove'></button>

                            </td>
                        </tr>
                    </tbody>
                </form>
                </table>
            </div>
        </div>
        <a id="add_row" class="btn btn-default pull-right">Add Class </a>
        <input type= "submit" value = "Finish" class="btn btn-success pull-right">
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