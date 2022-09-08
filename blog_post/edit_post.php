<?php
    include '../includes/header.php';
    include '../includes/database.php';
    include '../includes/errors.php';


    $id = $_GET['id'] ?? null;


    $statement = $pdo->prepare('SELECT * FROM post WHERE id=:id');
    $statement->bindValue(':id',$id);
    $statement->execute();
    $post = $statement->fetchAll(PDO::FETCH_ASSOC);


    $title = $post[0]['title'];
    $content = $post[0]['content'];


    if($_SERVER['REQUEST_METHOD']==='POST'){
        $title = $_POST['title'];
        $content = $_POST['ckeditor'];


        $statement = $pdo->prepare("UPDATE post SET title=:title, updatedAt=:updatedAt, content=:content WHERE id= :id");
        $statement->bindValue(':title',$title);
        $statement->bindValue(':updatedAt',date('Y-m-d H:i:s'));
        $statement->bindValue(':content',$content);
        $statement->bindValue(':id',$id);
        $statement->execute();

        header("Location:post_detail.php");
      }


?>



<div class="m-5">
  <a class="m-2" href="../">
      <i class="fas fa-arrow-left"></i>
  </a>
  <p class="display-5 mb-1 text-center">Edit Post</p>
  <form method="POST">
      <div class="mb-2 ">
        <label for="exampleFormControlInput1" class="form-label">Title</label>
        <input type="text" class="form-control"  value="<?php echo $title?>"  name="title" placeholder="Engineering" required>
      </div>

      <div class="mb-2 ">
        <label for="exampleFormControlTextarea1" class="form-label">Content</label>
        <textarea class="form-control" name="ckeditor" rows="4" placeholder="..."><?php echo $content?></textarea required>
      </div>

      <button type="submit" class="btn btn-primary btn-default"style="padding-left: 1.5rem; padding-right: 1.5rem;">Edit</button>
 </form>

</div>


<script>
  CKEDITOR.replace( 'ckeditor' );
</script>
