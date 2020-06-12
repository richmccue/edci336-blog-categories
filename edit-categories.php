<?php
  require('db.php');
  #$post_id = $_GET["post_id"];
  $blog_id = $_COOKIE["blog_id"];
  $blog_url = $_COOKIE["blog_url"];
  $section = $_COOKIE["section"];

  if(isset($_GET["post_id"])){
    $post_id = $_GET["post_id"];
    setcookie("post_id",$post_id);
  } else {
    $post_id = $_COOKIE["post_id"];
  }
?>
<html>
 <head>
   <script type="text/javascript">
     window.onload = function() {
       document.getElementById("categories").focus();
       document.getElementById("categories").select();
     };
   </script>
 </head>
 <body>
 <h1>EDCI 336 Blog Tracker - Edit Category</h1>

<?php
$query = "SELECT * FROM posts WHERE post_id =" . $post_id;
mysqli_query($db, $query) or die(mysqli_error());
$result = mysqli_query($db, $query);

echo "<form name='form' method='post' action=''>";

# If the form was submitted update posts table with the grade assigned.
if(isset($_POST['new']) && $_POST['new']==1) {
  $update = "UPDATE posts SET categories='" . $_REQUEST['categories'] .
    "' WHERE post_id=" . $_REQUEST['post_id'];
  mysqli_query($db, $update) or die(mysqli_error($db));
  echo "<p style='color:#FF0000;''>Record Updated Successfully!</p>";
  echo "<a href='harvest-orig.php?id=" . $blog_id . "'>Return to student's blog posts</a>";
  ?>
    <script> window.location.href = "<? echo "harvest-orig.php?id=" . $blog_id  ?>" </script>;
  <?php
} else {
  #If no new category assigned, display current category.
  echo "<form name='form' method='post' action=''>";
  echo "<input type='hidden' name='new' value='1' />";
  while ($row = mysqli_fetch_assoc($result)) {
    echo "<p><a href='" . $row['post_url'] . "' target='_blank'>" . $row['post_title'] . "</a></p>";
    echo "<p>Category <input name='categories' id='categories' size='80' value='" . $row['categories'] . "' /></p>";
    echo "<input name='post_id' type='hidden' value='" . $row['post_id'] . "' />";
    echo "<input name='blog_id' type='hidden' value='" . $row['blog_id'] . "' />";
  }
  echo "<p><input name='submit' type='submit' value='Update' /></p>";
  echo "</form>";
}

echo "</form>";

 mysqli_close($db);
 ?>

</body>
</html>