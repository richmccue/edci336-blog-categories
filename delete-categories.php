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

# $query = "SELECT * FROM posts WHERE post_id =" . $post_id;
# "UPDATE MyGuests SET lastname='Doe' WHERE id=2";

$query = "UPDATE posts SET categories = 'other' WHERE post_id =" . $post_id;
mysqli_query($db, $query) or die(mysqli_error($db));
$result = mysqli_query($db, $query);

mysqli_close($db);

header( "Location: harvest-orig.php?id=" . $blog_id );
exit ;

 
 ?>

