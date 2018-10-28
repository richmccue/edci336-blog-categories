<?php
  require('db.php');
?>
<html>
  <head>
    <title>Harvest New EDCI 336 Blog Posts</title>
  </head>
  <body>
    <?php
      //Feed URLs
      $feeds = array(
          "https://brechanbird.wordpress.com/feed",
          "https://hilarygraham11.wordpress.com/feed"
      );

      //Read each feed's items
      $entries = array();
      foreach($feeds as $feed) {
          $xml = simplexml_load_file($feed);
          $entries = array_merge($entries, $xml->xpath("//item"));
      }

      //Sort feed entries by pubDate
      usort($entries, function ($feed1, $feed2) {
          return strtotime($feed2->pubDate) - strtotime($feed1->pubDate);
      });

      ?>

      <ul><?php
      //Print all the entries
      foreach($entries as $entry){
          ?>
          <li><a href="<?= $entry->link ?>"><?= $entry->title ?></a> URL: <?= $entry->link ?><br />
          <?= strftime('%m/%d/%Y %I:%M %p', strtotime($entry->pubDate)) ?><br />
          Categories: <i><?= implode('</i>, <i>', (array)$entry->category) ?></i><br /></li>
          <?php
      }
      mysqli_close($db);
      ?>
      </ul>

  </body>
</html>
