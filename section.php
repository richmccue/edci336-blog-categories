<?php
  require('db.php');

  if(isset($_GET["section"])){
    $section = $_GET["section"];
    setcookie("section",$section);
    echo "Cookie set to: " . $_COOKIE["section"];
  } else {
    $section = $_COOKIE["section"];
    echo "Cookie value: " . $_COOKIE["section"];
    #$section = "2018-A03";
  }
#  if(isset($_GET["section"])) {
#    $section = $_GET["section"];
#    setcookie("section",$section);
#    echo "Cookie set to: " . $_COOKIE["section"];
#  } else {
#    $section = $_COOKIE["section"];
#    echo "Cookie value is: " . $_COOKIE["section"];
#  }

?>
<html>
 <head>
 </head>
 <body>
 <h1><a href="index.php">EDCI 336</a> <? echo $section; ?></h1>

<table>
  <tr><td><b>URL</b></td><td><b>Inquiries</b></td></tr>
 <?php
 //Step2
 $query = "SELECT * FROM blogs WHERE section='". $section . "'";
 mysqli_query($db, $query) or die('Error querying database.');

 //Step3
 $result = mysqli_query($db, $query);

 while ($row = mysqli_fetch_assoc($result)) {
  // Check, if not have http:// or https:// then prepend it
  $blog_url_no_http = $row['blog_url'];
  $blog_url_no_http = preg_replace( "#^[^:/.]*[:/]+#i", "", $blog_url_no_http );

  echo '<tr><td><a href="' .
    'http://msystems.net/edci336/harvest-orig.php?id='. $row['blog_id'] . '&section=' .
    $section . '&' . 'blog_url=' . $blog_url_no_http . '/feed/">' .
    $row['blog_url'] . '</a></td><td>' . $row['comment'] .'</td></tr>';
 }
 //Step 4
 mysqli_close($db);
 ?>
</table>

</body>
</html>
