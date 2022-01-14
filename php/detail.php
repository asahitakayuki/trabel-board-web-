<?php
session_start();
require('../php/dbconnect.php');
if(!isset($_SESSION['id']) && !isset($_SESSION['name'])){
  header('Location: ../php/login.php');
  exit();
}
?>


<!DOCTYPE html>
<html lang="jp">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="旅の情報を共有する掲示板サイト">
  <meta name="keywords" content="旅, 情報共有">
  <meta property="og:title" content="Trabel Board ~旅の掲示板~">
  <meta property="og:type" content="article">
  <meta property="og:description" content="旅の情報を共有する掲示板サイト">
  <meta property="og:site_name" content="プログラミング教材">
  <link rel="stylesheet" type="text/css" href="../css/reset.css">
  <link rel="stylesheet" type="text/css" href="../css/header.css">
  <link rel="stylesheet" type="text/css" href="../css/post.css">
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Murecho&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@700&display=swap" rel="stylesheet">

  <title>Trabel Board ~旅の掲示板~</title>
</head>


<body>
  <!----------header--------->
  <header>
    <nav class="header_nav">
      <ul class="header_ul">
        <li class="home_icon"><a href="../html/index.html"><img src="../img/outline_home_black_24dp.png" alt="ホームアイコン"></a>
        </li>
  
        <div class="header_li">
          <li class="header_li2"><a href="../php/library.php">Library</a></li>
          <li class="header_li2"><a href="../php/community.php">Community</a></li>
          <li class="header_li2"><a href="../php/contact.php">Contact</a></li>
        </div>
  
      </ul>
    </nav>
  </header>

  <!----------相手の投稿内容を閲覧---------->
  <?php
  $db = dbconnect();
  $stmt = $db->prepare('select p.id, p.message, p.picture, p.time, m.name from posts p, members m where m.id=p.members_id order by id desc');
  if(!$stmt){
   die($db->error);
  }
  $success = $stmt->execute();
  if (!$success) {
   die($db->error);
  }

  $stmt->bind_result($post_id, $message, $picture, $time, $name);
  $stmt->fetch();
  ?>

  <session class="post">

    
      
      <form action="" method="post">
      <div class="but">
       <button type="submit">いいねを押す</button>
      </div>
      
    </form>
  </session>
</body>
</html>