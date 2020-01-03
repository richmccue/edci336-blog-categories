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
$query = "SELECT * FROM posts WHERE post_id =" . $post_id;
mysqli_query($db, $query) or die(mysqli_error());
$result = mysqli_query($db, $query);

echo "<form name='form' method='post' action=''>";

# If the form was submitted update posts table with the grade assigned.
if(isset($_POST['new']) && $_POST['new']==1) {
  $update = "UPDATE posts SET grade=" . $_REQUEST['grade'] . " WHERE post_id=" . $_REQUEST['post_id'];
  mysqli_query($db, $update) or die(mysqli_error());
  echo "<p style='color:#FF0000;''>Record Updated Successfully!</p>";
} else {
#If no grade was assigned, display current grade (if any).
  while ($row = mysqli_fetch_assoc($result)) {
  echo "<p><a href='" . $row['post_url'] . "' target='_blank'>" . $row['post_title'] . "</a></p>";
  echo '<p>Grade: ' . $row['grade'] . '</p>';
  echo '<p>Notes: ' . $row['notes'] . '</p>';
  echo '<p>Post ID: ' . $row['post_id'] . '</p>';
  }
}

echo "</form>";

 mysqli_close($db);
 ?>

</body>
</html>
