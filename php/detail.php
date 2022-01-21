<?php
session_start();
require('../php/dbconnect.php');

if(isset($_SESSION['id']) && isset($_SESSION['name'])){
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
} else{
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
  <link rel="stylesheet" type="text/css" href="../css/footer.css">
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

  
  <?php
//いいね機能
 $post_id = $_GET['page'];
 $my_like_cnt = '';
 if($_SERVER['REQUEST_METHOD'] === 'POST'){ 
  
  //過去にいいね済みであるか確認
  $db = dbconnect();
  $stmt = $db->prepare('SELECT COUNT(*) AS cnt FROM likes WHERE posts_id=? AND members_id=?');
  if(!$stmt){
   die($db->error);
  } 
  $stmt->bind_param('ii', $post_id, $id);
  $success = $stmt->execute();
  if(!$success){
   die($db->error);
  }
  $stmt->bind_result($my_like_cnt);
  $stmt->fetch();
   
  //いいねのデータを挿入or削除
    

  if ($my_like_cnt < 1) {
    $db = dbconnect();
    $stmt = $db->prepare('INSERT INTO likes (posts_id, members_id) VALUES (?, ?)');
    if(!$stmt){
     die($db->error);
    }
    $stmt->bind_param('ii', $post_id, $id);
     
    $success = $stmt->execute();
    if(!$success){
     die($db->error);
    }

    header("Location: ../php/detail.php?page={$post_id}");
    exit();

  } else {
    $db = dbconnect();
    $stmt = $db->prepare('DELETE FROM likes WHERE posts_id=? AND members_id=?');
    if(!$stmt){
     die($db->error);
    }
    $stmt->bind_param('ii', $post_id, $id);
    $success = $stmt->execute();
    if(!$success){
     die($db->error);
    }

    header("Location: ../php/detail.php?page={$post_id}");   
    exit();
  } 
}else{
  //いいね押す前に、過去にいいね済みであるか確認
  $db = dbconnect();
  $stmt = $db->prepare('SELECT COUNT(*) AS cnt FROM likes WHERE posts_id=? AND members_id=?');
  if(!$stmt){
   die($db->error);
  } 
  $stmt->bind_param('ii', $post_id, $id);
  $success = $stmt->execute();
  if(!$success){
   die($db->error);
  }
  $stmt->bind_result($my_like_cnt);
  $stmt->fetch();
}
  
  //1つの投稿の合計いいね数
  $db = dbconnect();
 $stmt = $db->prepare('SELECT count(*) as cnt from likes where posts_id=?');
if(!$stmt){
 die($db->error);
}
$stmt->bind_param('i', $post_id);
$success = $stmt->execute();
if(!$success){
 die($db->error);
}

$stmt->bind_result($likes_post_cnt);
$stmt->fetch();


//----------相手の投稿内容を閲覧---------
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

<session>
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

  <!----------戻るボタン---------->
  <form action="" method="post">
  <div class="but">
    <div class="back_but_wrappe">
     <button class="back_but" type="submit"><a href="../php/community.php">戻る</a></button>
    </div>
   <!----------いいねボタン---------->
  
    <div class="good_but">
      <?php if ($my_like_cnt < 1) : ?>
       <button class="none" type="submit">&#9825;</button>
      <?php else : ?>
       <button class="red" type="submit">&#9825;</button>
      <?php endif; ?>
       <span><?php echo h($likes_post_cnt); ?></span>
    </div>
  </div>
  </form>

</div>  
<?php else :?>
 <p class="detail_blank">投稿がありません。</p>
<?php endif ;?>

</session>
  
  
  
  <!----------footer---------->
 <section class="footer">
  <p>©2021.○○.○○ Asahi Takayuki All Rights Reserved</p>
 </section>

</body>
</html>