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
            $feeds[$row["blog_id"]] = $row["blog_url"] . '/feed/';
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
###############
          # lookup 'blog_id' from 'blogs' table for this blog posts
          $blog_post_url = $entry->link;  ## Need to finish this!!!!
          $blogs_sql = "SELECT * FROM blogs WHERE blog_url = '" . $blog_post_url . "'";
          $blogs_result = mysqli_query($db, $blogs_sql);
          $blog_id = $blogs_result["blog_id"];
###############
          # Insert...
          $insert_post = "INSERT INTO posts (post_url, categories, date, blog_id)
            VALUES ('$entry->link', '$categories','$pubdate',$blog_id)";
            if (mysqli_query($db, $insert_post)) {
                echo "<b>New record created successfully</b><br />";
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
