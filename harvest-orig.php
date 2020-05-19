<?php
  require('db.php');
  #$blog_url = "http://" . $_GET["blog_url"];
  #$blog_id = $_GET["id"];
  $section = $_COOKIE["section"];

  if(isset($_GET["blog_url"])){
    $blog_url = "http://" . $_GET["blog_url"];
    setcookie("blog_url",$blog_url);
  } else {
    $blog_url = $_COOKIE["blog_url"];
  }

  if(isset($_GET["id"])){
    $blog_id = $_GET["id"];
    setcookie("blog_id",$blog_id);
  } else {
    $blog_id = $_COOKIE["blog_id"];
  }
?>
<html>
  <head>
    <title>EDCI 336 <? echo $section ?> Blog Posts</title>
  </head>
  <body>
    <h1>EDCI 336 <a href="section.php?section=<? echo $section ?>"><? echo $section ?></a> - <? echo $blog_url ?></h1>
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
              echo "New Blog Post: ". $entry->title . "<br>";
            } else {
              echo "Error: " . $sql . "<br>" . $conn->error;
            }
        }
      }

      //Print all posts on web page.
      $blog_count = 0;
      $sql = "SELECT * FROM posts WHERE blog_id = $blog_id ORDER BY date DESC";
      $result = mysqli_query($db, $sql);
      $EdTech = 0;
      $EdTechInquiry = 0;
      $FreeInquiry = 0;
      $Other = 0;

      echo "<table border='1'>";
      echo "<tr><th>#</th><th>Blog Title</th><th>Note</th><th>Post Date</th><th>Categories</th></tr>";
      while($row = mysqli_fetch_array($result)) {
          $blog_count += 1;
          $grade = $row['grade'];
          echo "<tr><th>" . $blog_count . "</th>";
          echo "<td><a href='" . $row['post_url'] . "' target='_blank'>" . $row['post_title'] . "</a></td>";
          echo "<td><b>" . $grade . "</b> <- <a href='grade-post.php?post_id=" . $row['post_id']
            . "'>Edit</a></td>";
          echo "<td>" . $row['date'] . "</td>";
          echo "<td><i>" .$row['categories'] . "</i></td></tr>\n";
          if ($row['categories'] == "EdTech") {
            $EdTech += 1;
          } elseif ($row['categories'] == "EdTech Inquiry") {
            $EdTechInquiry += 1;
          } elseif ($row['categories'] == "Free Inquiry") {
            $FreeInquiry += 1;
          } else {
            $Other += 1;
          }
        }
      echo "</table>";

      $categories_text .= "EdTech: " . $EdTech . " -- ";
      $categories_text .= "EdTechInq: " . $EdTechInquiry . " -- ";
      $categories_text .=  "FreeInq: " . $FreeInquiry . " -- ";
      $categories_text .=  "Oth: " . $Other . " ";
      echo $categories_text;

      #Write Categories stats to blogs table
      $sql = "UPDATE blogs SET category_count = '" . $categories_text ."' WHERE blog_id = " . $blog_id;

      if (mysqli_query($db, $sql)) {
          echo "";
      } else {
          echo "Error updating record: " . mysqli_error($conn);
      }

        mysqli_close($db);
      ?>
  </body>
</html>
