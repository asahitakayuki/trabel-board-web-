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
  <link rel="icon" type="image/png" href="../img/k0754_5.png" >
  <link rel="stylesheet" type="text/css" href="../css/reset.css">
  <link rel="stylesheet" type="text/css" href="../css/header.css">
  <link rel="stylesheet" type="text/css" href="../css/library.css">
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
  //詳細画面からユーザー情報を表示する
  if(isset($_GET['user'])){
    $user_id = $_GET['user'];

  $db = dbconnect();
  $stmt = $db->prepare('select name from members where id=?');
  if(!$stmt){
   die($db->error);
  }
  $stmt->bind_param('i', $user_id);
  $success = $stmt->execute();
  if(!$success){
    die($db->error);
  }

  $stmt->bind_result($user_name);
  $stmt->fetch();
  }

//投稿件数を取り出す
  $db = dbconnect();
  $stmt = $db->prepare('select count(*) as cnt from posts where members_id=?');
  if(!$stmt){
   die($db->error);
  }

  if(isset($_GET['user'])){
  $stmt->bind_param('i', $user_id);
  } else {
  $stmt->bind_param('i', $id);
  }
  $success = $stmt->execute();
  if(!$success){
    die($db->error);
  }

  $stmt->bind_result($cnt);
  $stmt->fetch();
  
  //いいね数を取り出す
  $db = dbconnect();
  $stmt = $db->prepare('SELECT COUNT(*) FROM posts AS p RIGHT JOIN likes AS l ON p.id=l.posts_id WHERE p.members_id=?');
  if(!$stmt){
   die($db->error);
  }

  if(isset($_GET['user'])){
  $stmt->bind_param('i', $user_id);
  } else {
  $stmt->bind_param('i', $id);
  }
  $success = $stmt->execute();
  if(!$success){
    die($db->error);
  }

  $stmt->bind_result($like_cnt);
  $stmt->fetch();

  ?>

  <!----------library---------->
  <session class="library">
    <div class="wrapper">
      <?php if(isset($_GET['user'])) :?>
      <h2 class="name"><?php echo h($user_name);?></h2>
      <?php else :?>
      <h2 class="name"><?php echo h($name);?></h2>
      <?php endif; ?>
    </div>
    
    <div class="user_info_wrappe">
    <div class="user_info">
     <p>投稿数<span><?php echo $cnt ; ?></span></p>
     <p>いいね数<span><?php echo $like_cnt; ?></span></p>

      <div class="post_but">
        <?php if(isset($_GET['user'])) :?>
        <button type="submit"><a href="../php/library_favorite.php?user=<?php echo h($user_id) ; ?>">いいねした投稿を見る</a></button>
        <?php else: ?>
       <button type="submit"><a href="../php/library_favorite.php">いいねした投稿を見る</a></button>
       <?php endif ; ?>
      </div>
      <div class="post_but">
       <button type="submit"><a href="../php/post.php">投稿する</a></button>
      </div>
    </div>
    </div>
  </session>

<?php
//投稿内容を表示する
$db = dbconnect();
$stmt = $db->prepare('select p.message, p.picture, p.time from posts as p where members_id=? order by id desc');

if(isset($_GET['user'])){
$stmt->bind_param('i', $user_id);
} else {
$stmt->bind_param('i', $id);
}

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
     <p class="library_time"><?php echo h($time); ?></p>
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