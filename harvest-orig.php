<?php
  require('db.php');
  $blog_url = "http://" . $_GET["blog_url"];
  $blog_id = $_GET["id"];
  $section = $_GET["section"];
?>
<html>
  <head>
    <title>EDCI 336 <? echo $section ?> Blog Posts</title>
  </head>
  <body>
    <h1>EDCI 336 <? echo $section ." - " . $blog_url ?></h1>
    <?php
      $feeds = array(
          #"https://alexandralyner.wordpress.com/feed",
          "$blog_url",
      );

      //Read each feed's items
      $entries = array();
      foreach($feeds as $feed) {
          $xml = simplexml_load_file($feed);
          $entries = array_merge($entries, $xml->xpath("//item"));
      }
      ?>

      <?php
      //Add all new blog posts into the posts table
      foreach($entries as $entry){
        $sql = "SELECT * FROM posts WHERE post_url = '" . $entry->link . "'";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) > 0) {
            #echo "Already Added<br>";
        } else {
          #add new blog post to "posts" table
          $categories = implode(', ', (array)$entry->category);
          $pub_date = strftime('%Y-%m-%d', strtotime($entry->pubDate));
          $sql = "INSERT INTO posts (post_url, 	categories, date, blog_id, post_title)
            VALUES ('$entry->link', '$categories', '$pub_date', $blog_id,'$entry->title')";
            if ($db->query($sql) === TRUE) {
              echo "New record created successfully". $sql . "<br>";
            } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
      }

      //Print all posts on web page.

      $sql = "SELECT * FROM posts WHERE blog_id = $blog_id";
      $result = mysqli_query($db, $sql);

      echo "<table border='1'>";
      while($row = mysqli_fetch_array($result)) {
          $grade = $row['grade'];
          echo "<tr><td><a href='" . $row['post_url'] . "'>" . $row['post_title'] . "</a></td>";
          echo "<td><form id='save_grade' action='save_grade.php' method='post'>";
          echo "<input type='text' name='grade' size='2' value='" . $grade . "'>";
          echo "<a href='#' onclick='document.getElementById('save_grade').submit();'>Save</a></form></td>";
          echo "<td>" . $row['date'] . "</td>";
          echo "<td><i>" .$row['categories'] . "</i></td></tr>\n";
        }
      echo "</table>";

      mysqli_close($db);
      ?>

  </body>
</html>
