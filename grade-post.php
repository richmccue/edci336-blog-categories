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
  $update = "UPDATE posts SET grade=" . $_REQUEST['grade'] . ", notes='" . $_REQUEST['notes'] .
    "' WHERE post_id=" . $_REQUEST['post_id'];
  mysqli_query($db, $update) or die(mysqli_error());
  echo "<p style='color:#FF0000;''>Record Updated Successfully!</p>";
} else {
#If no grade was assigned, display current grade (if any).
  echo "<form name='form' method='post' action=''>";
  echo "<input type='hidden' name='new' value='1' />";
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<p><a href='" . $row['post_url'] . "' target='_blank'>" . $row['post_title'] . "</a></p>";
    echo "<p>Grade <input name='grade' value='" . $row['grade'] . "' /> 3=Excellent, 2=OK, 1=Needs Work</p>";
    echo "<p>Notes <input name='notes' value='" . $row['notes'] . "' /></p>";
    echo "<input name='post_id' type='hidden' value='" . $row['post_id'] . "' />";
  }
  echo "<p><input name='submit' type='submit' value='Update' /></p>";
  echo "</form>";
}

echo "</form>";

 mysqli_close($db);
 ?>

</body>
</html>
