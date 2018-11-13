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
      #$sql = "SELECT * FROM blogs";
      #$result = mysqli_query($db, $sql);
      #if (mysqli_num_rows($result) > 0) {
      #    // output data of each row
      #    while($row = mysqli_fetch_assoc($result)) {
      #      # echo 'url: ' . $row["blog_url"] . '</br>';
      #      $feeds[] = $row["blog_url"] . '/feed/';
      #    }
      #} else {
      #    echo "0 results";
      #}
      $feeds = array(
          "https://benston789106021.wordpress.com/feed",
      );

      //Read each feed's items
      $entries = array();
      foreach($feeds as $feed) {
          $xml = simplexml_load_file($feed);
          $entries = array_merge($entries, $xml->xpath("//item"));
      }

      //Sort feed entries by pubDate
      # usort($entries, function ($feed1, $feed2) {
      #    return strtotime($feed2->pubDate) - strtotime($feed1->pubDate);
      #});
      ?>

      <ul><?php
      //Add all new blog posts into the posts table
      foreach($entries as $entry){
        $sql = "SELECT * FROM posts WHERE post_url = " . $entry->link;
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) > 0) {
            echo "already added";
        } else {
          #add new blog post to "posts" table
          while($row = mysqli_fetch_assoc($result)) {
            $feeds[] = $row["blog_url"] . '/feed/';
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
