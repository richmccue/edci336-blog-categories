<?php
  include("db.php");
?>
<html>
 <head>
 </head>
 <body>
 <h1>EDCI 336 A03 Blogs</h1>

 <?php
 //Step2
 $query = "SELECT * FROM blogs";
 mysqli_query($db, $query) or die('Error querying database.');

 //Step3
 $result = mysqli_query($db, $query);
 $row = mysqli_fetch_array($result);

 while ($row = mysqli_fetch_array($result)) {
  echo $row['blog_id'] . ' <a href="' . $row['blog_url'] . '">' . $row['blog_url'] . '</a> ' . $row['comment'] .'<br />';
 }
 //Step 4
 mysqli_close($db);
 ?>

</body>
</html>
