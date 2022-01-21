<?php
session_start();
require('../php/dbconnect.php');
if(!isset($_SESSION['id']) && !isset($_SESSION['name'])){
  header('Location: ../php/login.php');
  exit();
}

$keyword = '';

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
       <input class="search_form" type="text" name="search" value="<?php echo h($keyword) ;?>" placeholder="キーワードを入力">
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

 $db = dbconnect();

 //最大ページ数を求める
$count = $db->query('select count(*) as cnt from posts');
$count = $count->fetch_assoc();
$max_page = floor(($count['cnt']-1)/10+1);


 if($_SERVER['REQUEST_METHOD'] !== 'POST'){ 
 
 $stmt = $db->prepare('select p.id, p.message, p.picture, p.time, m.name from posts  p, members m where m.id=p.members_id order by id desc limit ?, 10');

 $stmt->bind_param('i', $start);

 } else { 
 //検索機能部分
 $word = filter_input(INPUT_POST, 'search', FILTER_SANITIZE_STRING);
 $keyword = "%$word%" ;
 $stmt = $db->prepare('SELECT p.id, p.message, p.picture, p.time, m.name 
 FROM posts AS p LEFT JOIN members AS m ON m.id=p.members_id WHERE p.message LIKE ? ORDER BY p.id DESC limit ?, 10');

 $stmt->bind_param('si', $keyword, $start);
 }

 $page = filter_input(INPUT_GET, 'pagenation', FILTER_SANITIZE_NUMBER_INT);
  if(!$page){
    $page = 1;
  }
  $start = ($page - 1) * 10;

  
  
  $success = $stmt->execute();
  if(!$success){
   die($db->error);
  }
 $stmt->bind_result($post_id, $message, $picture, $time, $name);

//1つの投稿の合計いいね数
 /*$likes = $db->prepare('SELECT count(*) as cnt from likes where posts_id=?');
  if(!$likes){
   die($db->error);
  }
  $likes->bind_param('i', $post_id);
  $likes_exe = $stmt->execute();
  if(!$likes_exe){
   die($db->error);
  }
  $likes->bind_result($likes_post_cnt);
  while($likes->fetch()){
  }*/
  
 while($stmt->fetch()):
?>
  
  <section class="community">
    <a href="../php/detail.php?page=<?php echo $post_id ?>">
    <div class="post_list">
      <div class="post_list_inner">
        <div class="post_name_wrappe">
         <h3 class="post_name"><?php echo h($name);?></h3>
         <p class="post_good">いいね○○</p>
         <p class="post_time"><?php echo h($time) ;?></p>
        </div>
       <p class="post_content">
         <?php if(mb_strlen($message) > 80){
          echo mb_substr(h($message), 0, 80 ) . '.....';
         } else{
          echo h($message);
         } ?>
       </p>
      </div>
      <div class="post_img_wrappe">
      <?php if ($picture) :?>
      <a href="../post_img/<?php echo $picture ;?>" class="post_img_a" data-lightbox="group" ><img class="post_img" src="../post_img/<?php echo $picture ;?>"></a>
      <?php endif ;?>
      <div>
    </div>
  </a>
</section>
<?php endwhile; ?>

<div class="page">
  <?php if($page > 1): ?>
  <div class="page_but page_back">
   <button type="submit"><a href="?pagenation=<?php echo $page-1;?>"><?php echo $page-1;?></a>
  </div>
  <?php endif; ?>

  <?php if($page < $max_page): ?>
  <div class="page_but page_next">
   <button type="submit"><a href="?pagenation=<?php echo $page+1;?>"><?php echo $page+1;?></a>
  </div>
  <?php endif; ?>
</div>

</main>
</body>
</html>