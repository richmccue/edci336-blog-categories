<?php
  require('db.php');
?>
<html>
  <head>
    <title>Harvest New EDCI 336 Blog Posts</title>
  </head>
  <body>
    <?php
      //Put all RSS Feed URLs into array $feeds[]
      $sql = "SELECT * FROM blogs";
      $result = mysqli_query($db, $sql);

      if (mysqli_num_rows($result) > 0) {
          // output data of each row
          while($row = mysqli_fetch_assoc($result)) {
            $feeds[] = $row["blog_url"] . '/feed/';
            $blog_id[] = $row["blog_id"];
          }
      } else {
          echo "0 results";
      }

      //Read each feed's items
      $entries = array();
      foreach($feeds as $feed) {
          $xml = simplexml_load_file($feed);
          $entries = array_merge($entries, $xml->xpath("//item"));
      }
      ?>

      <ul><?php
      //Add all new blog posts into the posts table
      foreach($entries as $entry){
        $sql = "SELECT * FROM posts WHERE post_url = '" . $entry->link . "'";
        print $entry->link . " - looked up<br />";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "already added<br />";
        } else {
          #add new blog post to "posts" table
          $categories = implode(', ', (array)$entry->category);
          $pubdate = date('Y-m-d',strtotime($entry->pubDate));
          $insert_post = "INSERT INTO posts (post_url, categories, date)
            VALUES ('$entry->link', '$categories','$pubdate')";
            if (mysqli_query($db, $insert_post)) {
                echo "New record created successfully";
            } else {
                echo "Error: " . $insert_post . " - " . mysqli_error($db);
            }
        }
      }

      mysqli_close($db);
      ?>
      </ul>
  </body>
</html>
