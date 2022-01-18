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
  <link rel="stylesheet" type="text/css" href="../css/library.css">
  <link rel="stylesheet" type="text/css" href="../css/detail&library.css">
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Murecho&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@700&display=swap" rel="stylesheet">

  <title>Trabel Board ~旅の掲示板~</title>
</head>


<body>
  <!----------header--------->
  <header class="header_all library_img img_conf img_ams">
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
    <h2 class="header_title">Library</h2>
  </header>

  <?php
  //投稿件数を取り出す

  

  $db = dbconnect();
  $stmt = $db->prepare('select count(*) as cnt from posts where members_id=?');
  if(!$stmt){
   die($db->error);
  }

  $stmt->bind_param('i', $id);
  $success = $stmt->execute();
  if(!$success){
    die($db->error);
  }

  $stmt->bind_result($cnt);
  $stmt->fetch();
  
  ?>

  <!----------library---------->
  <session class="library">
    <div class="wrapper">
     <h2 class="name">   
       <p><?php echo h($name);?></p>
      </h2>
      
    </div>
    
    <div class="user_info">
     <p>投稿数<span><?php echo $cnt ; ?></span></p>
     <p>いいね数<span><?php echo '○○'; ?></span></p>
     <p><a href="">いいねした投稿を見る</a></p>
     <div class="post_but">
       <button type="submit"><a href="../php/post.php">投稿する</a></button>
      </div>
    </div>
  </session>

<?php
//投稿内容を表示する
$db = dbconnect();
$stmt = $db->prepare('select p.message, p.picture, p.time from posts as p where members_id=? order by id desc');

$stmt->bind_param('i', $id);
$success = $stmt->execute();
if(!$success){
 die($db->error);
}

$stmt->bind_result($message, $picture, $time);
while($stmt->fetch()):
?>

<session class="post_content">
  <div class="detail_content">
    <div class="detail_name_wrappe">
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
<?php endwhile; ?>
</body>
</html>