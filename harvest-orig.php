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

      //Print all posts on web page & counts categories for the bottom of the table
      $blog_count = 0;
      $sql = "SELECT * FROM posts WHERE blog_id = $blog_id ORDER BY date DESC";
      $result = mysqli_query($db, $sql);
      $EdTech = 0;
      $EdTechInquiry = 0;
      $FreeInquiry = 0;
      $Other = 0;
      
      ##$cat_1 = "edci337-blog";
      #$cat_2 = "edci337-app";
      #$a_categories = array($cat_1=>"3",$cat_2=>"7");
      #foreach ($a_categories as $x=>$x_value) {
      #   echo "Key: " . $x . " and Value: " . $x_value . "<br>\n";     
      #}
      $a_categories = array();

      echo "<table border='1'>";
      echo "<tr><th>#</th><th>Blog Title</th><th>Note</th><th>Post Date</th><th>Categories</th><th>Edit Cat...</th></tr>\n";
      while($row = mysqli_fetch_array($result)) {
          $blog_count += 1;
          $grade = $row['grade'];
          $cat_temp = $row['categories'];
          if ($cat_temp != 'other') {
            echo "<tr><th>" . $blog_count . "</th>\n";
            echo "<td><a href='" . $row['post_url'] . "' target='_blank'>" . $row['post_title'] . "</a></td>\n";
            echo "<td><b>" . $grade . "</b> <- <a href='grade-post.php?post_id=" . $row['post_id'] . "'>Edit</a></td>\n";
            echo "<td>" . $row['date'] . "</td>\n";
            echo "<td><i>" . $cat_temp . "</i></td>\n";
            echo "<td><a href='delete-categories.php?post_id=" . $row['post_id'] . "'>clear</a> - 
            <a href='edit-categories.php?post_id=" . $row['post_id'] . "'>edit</a></td></tr>\n\n";
            
            if (array_key_exists($cat_temp,$a_categories)) {
              # how many in of this category type?
              $num_posts = $a_categories[$cat_temp];
              $a_categories[$cat_temp] = $num_posts + 1;
              # echo "Array key exists<br>\n";
            } else { 
              # Add new category for this blog to the array
              $a_categories[$cat_temp]="1";
            }
          }
        }
      echo "</table>";
      
      # Sort and then Print out all the categories and totals:
      ksort($a_categories);

      foreach ($a_categories as $x=>$x_value) {
        #echo $x . ": " . $x_value . "<br>\n";  
        $categories_text .= "<b>" . $x . ":</b> " . $x_value . " -- ";
      }

      echo "<br>" . $categories_text;

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
