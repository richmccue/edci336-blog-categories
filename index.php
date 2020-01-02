<?php
  require('db.php');
?>
<html>
 <head>
 </head>
 <body>
 <h1>EDCI 336 Sections</h1>

<table>
  <tr><td><b>id</b></td><td><b>Section</b></td><td><b>Instructor</b></td></tr>
 <?php
 //Step2
 $query = "SELECT * FROM sections";
 mysqli_query($db, $query) or die('Error querying database.');

 //Step3
 $result = mysqli_query($db, $query);
 $row = mysqli_fetch_array($result);

 while ($row = mysqli_fetch_array($result)) {

  echo '<tr><td>' . $row['section_id'] . '</td><td><a href="' .
    'http://msystems.net/edci336/section.php?id='. $row['section_id'] . ">' .
    $row['section'] . '</a></td><td>' . $row['intructor'] .'</td></tr>';
 }
 //Step 4
 mysqli_close($db);
 ?>
</table>

</body>
</html>
