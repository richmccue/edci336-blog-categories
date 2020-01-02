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

      <ul><?php
      //Add all new blog posts into the posts table
      foreach($entries as $entry){
        $sql = "SELECT * FROM posts WHERE post_url = '" . $entry->link . "'";
        $result = mysqli_query($db, $sql);


        if (mysqli_num_rows($result) > 0) {
            echo "Already Added<br>";
        } else {
          #add new blog post to "posts" table
          $categories = implode(', ', (array)$entry->category);
          $pub_date = strftime('%Y-%m-%d', strtotime($entry->pubDate));
          $sql = "INSERT INTO posts (post_url, 	categories, date, blog_id)
            VALUES ('$entry->link', '$categories', '$pub_date', $blog_id)";
            if ($db->query($sql) === TRUE) {
              echo "New record created successfully". $sql . "<br>";
            } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
      }

      //Print all posts on web page.
      foreach($entries as $entry){
          ?>
          <li><a href="<?= $entry->link ?>"><?= $entry->title ?></a>
          <?= strftime('%m/%d/%Y %I:%M %p', strtotime($entry->pubDate)) ?><br />
          Categories: <i><?= implode('</i>, <i>', (array)$entry->category) ?></i><br /></li>
          <?php
      }

      mysqli_close($db);
      ?>
      </ul>

  </body>
</html>
