<html>
<head> 
  <title>Atlas Admin - Show Courses</title> 
</head>

<body>
  <h1>Atlas Admin - Show Courses </h1>
  <?php

    $dbconn = pg_connect("host=localhost dbname=AtlasDB user=postgres password=Globe123") or die('Could not connect: ' . pg_last_error());

    $stat = pg_connection_status($dbconn);
  if ($stat === PGSQL_CONNECTION_OK) {
      echo 'Connection status ok';
  } else {
      echo 'Connection status bad';
  }    

  
    $query = 'SELECT * FROM maristcourses';
    $result = pg_query($query) or die('Query failed: ' . pg_last_error());

    $i = 0;

    echo '<html><body><table><tr>';
      while ($i < pg_num_fields($result))
      {
        echo '<td>' . $fieldName . '</td>';
        $i = $i + 1;
      }
        echo '</tr>';
  $i = 0;

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

pg_free_result($result);

pg_close($dbconnection);

?>
</body>
</html>
  


