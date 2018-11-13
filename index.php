<?php
  require('db.php');
?>
<html>
 <head>
 </head>
 <body>
 <h1>EDCI 336 A03 Blogs</h1>

<table>
  <tr><td><b>id</b></td><td><b>URL</b></td><td><b>Inquiries</b></td></tr>
 <?php
 //Step2
 $query = "SELECT * FROM blogs";
 mysqli_query($db, $query) or die('Error querying database.');

 //Step3
 $result = mysqli_query($db, $query);
 $row = mysqli_fetch_array($result);

 while ($row = mysqli_fetch_array($result)) {
  echo '<tr><td>' . $row['blog_id'] . '</td><td><a href="' .
    $row['blog_url'] . '">' . $row['blog_url'] . '</a></td><td>' .
    $row['comment'] .'</td></tr>';
 }
 //Step 4
 mysqli_close($db);
 ?>
</table>

</body>
</html>
