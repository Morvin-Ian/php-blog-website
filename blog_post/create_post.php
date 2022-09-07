<?php
    include '../includes/header.php';
    include '../includes/database.php';
    include '../includes/errors.php';
    session_start();

    $createdAt = date('Y-m-d H:i:s');
    $updatedAt = $createdAt;

    $session_author = $_SESSION['username'];
    $statement = $pdo->prepare("SELECT * FROM user WHERE username=:username");
    $statement->bindValue(':username', $session_author);
    $statement->execute();
    $author = $statement->fetchAll(PDO::FETCH_ASSOC);
    $authorId = $author[0]['id'];


    if($_SERVER['REQUEST_METHOD']==='POST'){
      $title = $_POST['title'];
      $content = $_POST['content'];

      $statement = $pdo->prepare("INSERT INTO post (title, authorId, createdAt, updatedAt, content)
                                  VALUES( :title, :authorId, :createdAt, :updatedAt, :content)");
      $statement->bindValue(':title', $title);
      $statement->bindValue(':authorId', $authorId);
      $statement->bindValue(':createdAt', $createdAt);
      $statement->bindValue(':updatedAt', $updatedAt);
      $statement->bindValue(':content', $content);
      $statement->execute();

      header("Location: ../index.php");

    }

?>

<div class="m-5">
  <a href="../">
      <i class="fas fa-arrow-left"></i>
  </a>
  <p class="display-5 mb-1 text-center">Create Post</p>
  <form method="POST">
      <div class="mb-2 ">
        <label for="exampleFormControlInput1" class="form-label">Title</label>
        <input type="text" class="form-control" name="title" placeholder="@example Engineering" required>
      </div>

      <div class="mb-2 ">
        <label for="exampleFormControlTextarea1" class="form-label">Content</label>
        <textarea class="form-control" name="content" rows="3" placeholder="..."></textarea required>
      </div>

      <button type="submit" class="btn btn-primary btn-default"style="padding-left: 2.5rem; padding-right: 2.5rem;">Post</button>
 </form>

</div>
