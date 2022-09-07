<?php
  include '../includes/database.php';
  include '../includes/errors.php';

  session_start();


  $postId = $_POST['id'];

  $session_username = $_SESSION['username'] ?? null;

  $statement = $pdo->prepare('SELECT * FROM user WHERE username=:username');
  $statement->bindValue(':username',$session_username);
  $statement->execute();
  $user = $statement->fetchAll(PDO::FETCH_ASSOC);
  $reactorId = $user[0]['id'];


  if($_SERVER['REQUEST_METHOD']==='POST'){

      $views = null;
      $comments =$_POST['comment'] ?? null;
      $subscriber = $_POST['subscriber'] ?? null;


      $statement = $pdo->prepare("INSERT INTO reactions (reactorId, postId, views, comments, subscribers)
                                  VALUES(:reactorId, :postId,  :views, :comments, :subscribers)");
      $statement->bindValue(":reactorId", $reactorId );
      $statement->bindValue(":postId", $postId );
      $statement->bindValue(":views", $views);
      $statement->bindValue(":comments", $comments);
      $statement->bindValue(":subscribers", $subscriber);
      $statement->execute();

      header("Location:../blog_post/post_detail.php");
  }



 ?>
