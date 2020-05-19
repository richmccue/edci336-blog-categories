<?php
  require('db.php');
?>
<html>
 <head>
 </head>
 <body>
 <h1>BPT or Blog Post Tracker 4 Instructors</h1>

<table>

  <tr><td><b>Section</b></td><td><b>Instructor</b></td></tr>
<?php
 //Step2
 $query = "SELECT * FROM section ORDER BY section DESC";
 mysqli_query($db, $query) or die('Error querying database.');

 //Step3
 $result = mysqli_query($db, $query);

 while ($row = mysqli_fetch_assoc($result)) {
  echo '<tr><td><a href="' .
    'http://msystems.net/edci336/section.php?section='. $row['section'] . '">' .
    $row['section'] . '</a></td><td>' . $row['instructor'] .'</td></tr>';
 }
 //Step 4
 mysqli_close($db);
 ?>
</table>

</body>
</html>
