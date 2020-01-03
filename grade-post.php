<?php
  require('db.php');
  $post_id = $_GET["post_id"];
?>
<html>
 <head>
 </head>
 <body>
 <h1>EDCI 336 Blog Tracker - Grade Post</h1>

<?php
 //Step2
 $query = "SELECT * FROM posts WHERE post_id =" . $post_id;
 mysqli_query($db, $query) or die('Error querying database.');

 //Step3
 $result = mysqli_query($db, $query);

 while ($row = mysqli_fetch_assoc($result)) {
  echo "<p><a href='" . $row['post_url'] . "' target='_blank'>" . $row['post_title'] . "</a></p>";
  echo '<p>Grade: ' . $row['grade'] . '</p>';
 }

 //Step 4
 mysqli_close($db);
 ?>

</body>
</html>
