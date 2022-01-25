<?php
session_start();
require('../php/dbconnect.php');

//uuid
require_once '../vendor/autoload.php';
  use Ramsey\Uuid\Uuid;


if(isset($_SESSION['id']) && isset($_SESSION['name'])){
  $id = $_SESSION['id'];
  $name = $_SESSION['name'];
} else{ 
  header('Location: ../php/login.php');
  exit();
}

$post = '';
$error = [];


if($_SERVER['REQUEST_METHOD'] === 'POST'){
  //メッセージのエラーチェック
  $post = (filter_input(INPUT_POST, 'post_content', FILTER_SANITIZE_STRING));
  if($post === ''){
   $error['post'] = 'blank';
  } else if(mb_strlen($post) > 300){
     $error['post'] = 'over';
  }

  //画像の形式チェック
  $image = $_FILES['image'];
  if ($image['name'] !== '' && $image['error'] === 0){
    $check_type = mime_content_type($image['tmp_name']);
    if ($check_type !== 'image/png' && $check_type !== 'image/jpeg'){ 
     $error['image'] = 'type' ;
    }
  }
  
  //エラーチェック通過

  if(empty($error)){
    //画像のアップロード
    if($image['name'] !== ''){

     $check_type = mime_content_type($image['tmp_name']);//形式をチェックする
      if($check_type === 'image/jpeg'){ 
      $uuid = Uuid::uuid4() . '.jpg';
    } else {
      $uuid = Uuid::uuid4() . '.png';
    }

     if (!move_uploaded_file($image['tmp_name'], '../post_img/'. $uuid)){
     die('ファイルのアップロードに失敗しました');
     }
    }
  
   $db = dbconnect();
   $stmt = $db->prepare('insert into posts (members_id, message, picture) values(?, ?, ?)');
    if(!$stmt){
     die($db->error);
    }
   $stmt->bind_param('iss', $id, $post, $uuid);
   $success = $stmt->execute();
    if(!$success){
     die($db->error);
    }

   header('Location: ../php/community.php');
   exit();
  }
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


  <!----------投稿内容を記述して送信する---------->
  <session class="post">
    <form action="" method="post" enctype="multipart/form-data">
      <textarea name="post_content" placeholder="テキストを入力、写真を挿入"><?php echo h($post); ?></textarea>

      <input type="file" name="image">
      
      <div class="but">
       <button type="submit">Communityに投稿する</button>
      </div>
      <p>写真等は、「jpeg」又は「png」の形式の画像を指定してください</p>

      <div class="error_wrappe">
      <?php if(isset($error['post']) && $error['post'] === 'blank'):?>
      <p class="error">* テキストを入力してください</p>
      <?php endif; ?>
      <?php if(isset($error['image']) && $error['image'] === 'type'):?>
      <p class="error">* 恐れ入りますが、画像を改めて指定してください</p>
      <?php endif; ?>
      <?php if(isset($error['post']) && $error['post'] === 'over'):?>
      <p class="error">* 文字数が超えています</p>
      <p class="error_int"><?php  echo '現在' . mb_strlen($post) . '文字'; ?>
      <?php endif; ?>
      </div>
    </form>
  </session>
</body>
</html>