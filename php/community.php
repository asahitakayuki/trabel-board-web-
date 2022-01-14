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
  <link rel="stylesheet" type="text/css" href="../css/community.css">
  <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@700&family=Murecho&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fuzzy+Bubbles:wght@700&display=swap" rel="stylesheet">
  
  <title>Trabel Board ~旅の掲示板~</title>
</head>



<body>
<!----------header--------->
  <header class="header_all community_img img_ams">
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
    <h2 class="header_title">Community</h2>
  </header>


<!----------community-検索機能-投稿ページへ移動ボタン＆---------->
<main>
  <section class="search">
  <form action="" method="post">
    
    <div class="search_wrapper">
      <div class="search_inner">
       <input class="search_form" type="text" name="search" placeholder="キーワードを入力">
       <div class="search_but">
        <button type="submit">検索</button>
      </div>
      </div>
      <div class="post_but">
       <button type="submit"><a href="../php/post.php">投稿する</a></button>
      </div> 
    </div>
  </form>
  </section>

<!------------community-投稿一覧----------->
<?php
/*if($_SERVER['REQUEST_METHOD'] === 'POST'){
$keyword = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
$db = dbconnect();
$stmt = $db->prepare("select p.id, p.message, p.picture, p.time, m.name from posts p, members m where message LIKE '%" . $keyword . "%' m.id=p.members_id order by id desc");
if(!$stmt){
 die($db->error);
}
$stmt->execute();
if (!$success) {
 die($db->error);
}
}*/


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
while($stmt->fetch()):

?>
  
  <section class="community"><a href="../php/detail.php?=<?php echo $post_id ?>">

    <div class="post_list">
      <div class="post_list_inner">
        <div class="post_name_wrappe">
         <h3 class="post_name"><?php echo mb_substr($name, 0, 70);?></h3>
         <p class="post_time"><?php echo $time ;?></p>
        </div>
       <p class="post_content"><?php echo $message ;?></p>
      </div>
      <div class="post_img_wrappe">
      <?php if ($picture) :?>
       <img class="post_img" src="../post_img/<?php echo $picture ;?>">
      <?php endif ;?>
      <div>
    </div>
  </a></section>
  
<?php endwhile; ?>
</main>
</body>
</html>