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
  <link rel="stylesheet" type="text/css" href="../css/detail&library.css">
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Murecho&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@700&display=swap" rel="stylesheet">

  <title>Trabel Board ~旅の掲示板~</title>

  <!----------写真拡大------------>
  <link href="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/css/lightbox.css" rel="stylesheet">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/lightbox2/2.7.1/js/lightbox.min.js" type="text/javascript"></script>
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
  $post_id = $_GET['id'];

  $db = dbconnect();
  $stmt = $db->prepare('select p.id, p.message, p.picture, p.time, m.name from posts p, members m where p.id=? and m.id=p.members_id ');
  if(!$stmt){
   die($db->error);
  }
  $stmt->bind_param('i', $post_id);

  $success = $stmt->execute();
  if (!$success) {
   die($db->error);
  }

  $stmt->bind_result($post_id, $message, $picture, $time, $name);
  if($stmt->fetch()):
  ?>

  <session class="detail">
  <div class="detail_content">
    <div class="detail_name_wrappe">
     <h3 class="detail_name"><?php echo h($name); ?></h3>
     <p class="detail_time"><?php echo h($time); ?></p>
    </div>
      
    <?php if ($picture) :?>
    <div class="detail_img_wrappe">
     <a href="../post_img/<?php echo $picture ;?>" data-lightbox="group"><img class="detail_img" src="../post_img/<?php echo $picture ;?>"></a>
    </div>
    <?php endif ;?>

    <div class="detail_message_wrappe">
     <p class="detail_message"><?php echo h($message); ?></p>
    </div>
  </div>  
  </session>
  <?php else :?>
    <p class="detail_blank">投稿がありません。</p>
  <?php endif ;?>

  <!----------戻るボタン、いいねボタン---------->
  
</body>
</html>