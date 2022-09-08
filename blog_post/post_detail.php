<?php

    include '../includes/header.php';
    include '../includes/database.php';
    include '../includes/errors.php';

    session_start();


    $id = $_POST['id'] ?? null;

    if(!$id){
        header("Location:../");
        exit;
    }

    // user Id
    $statement = $pdo->prepare('SELECT * FROM post WHERE id=:id');
    $statement->bindValue(':id',$id);
    $statement->execute();
    $post = $statement->fetchAll(PDO::FETCH_ASSOC);

    $postId = $post[0]['id'];

    $authorId = $post[0]['authorId'];

    // user picking
    $statement = $pdo->prepare('SELECT * FROM user WHERE id=:id');
    $statement->bindValue(':id',$authorId);
    $statement->execute();
    $author = $statement->fetchAll(PDO::FETCH_ASSOC);

    // posts of the same author/user
    $statement = $pdo->prepare('SELECT * FROM post WHERE authorId=:authorId ORDER BY createdAt DESC');
    $statement->bindValue(':authorId',$authorId);
    $statement->execute();
    $sameAuthor = $statement->fetchAll(PDO::FETCH_ASSOC);

    // selecting comments
    $statement = $pdo->prepare('SELECT * FROM reactions WHERE postId=:id');
    $statement->bindValue(':id',$id);
    $statement->execute();
    $row = $statement->rowCount();
    $comments = $statement->fetchAll(PDO::FETCH_ASSOC);
    $comment_rows=0;

    for($i=0;$i<$row;$i++){
        if($comments[$i]['comments']!=NULL){
            $comment_rows+=1;
        }

    }

    // Adding Views

    // updating existing views
    $statement = $pdo->prepare('SELECT * FROM postviews WHERE postId=:id');
    $statement->bindValue(':id',$id);
    $statement->execute();
    $row = $statement->rowCount();
    $views_count = $statement->fetchAll(PDO::FETCH_ASSOC);



    if($row>0){
        $views_existing = $views_count[0]["views"];
        $views = $views_existing + 1;
        $statement = $pdo->prepare("UPDATE postviews SET postId=:postId, views=:views WHERE postId=$id");
        $statement->bindValue(":postId", $id );
        $statement->bindValue(":views", $views);
        $statement->execute();
        $views_count = $statement->fetchAll(PDO::FETCH_ASSOC);

    }

    else{

        //Adding a new view (The first view)
        $views = $_POST['views'];
        $statement = $pdo->prepare("INSERT INTO postviews ( postId, views) VALUES(:postId,  :views)");
        $statement->bindValue(":postId", $id );
        $statement->bindValue(":views", $views);
        $statement->execute();

    }

?>

<div class=" m-5">
        <a style="color: black;" href="../index.php">
            <i class="fas fa-arrow-left"></i>
        </a>

        <h1><?php echo $post[0]['title']; ?></h1> <small>Updated: <?php echo $post[0]['updatedAt']; ?></small>

        <p> <?php echo $post[0]['content']; ?> </p>

          <small>
              <?php if(isset($views_existing)): ?>
                <i style="margin-right:30px;" ><em style="font-size:15px;">  <?php echo $views_existing; ?> view(s)</em></i>
              <?php endif;?>
              <a style="color: black; text-decoration:none;" href="blog_post/post_detail.php">
                <i <em style="font-size:15px;"> <?php echo $comment_rows; ?> </em> comment(s)</i>
              </a>

          </small>
          <div class="mt-2 mb-2">
             <br>  <img class="rounded-circle" src="<?php echo $author[0]['profile']; ?>" alt="" width='30px' height='30px'>
              <small><em>Written By: <?php echo $author[0]['lastName'] ,' '.$author[0]['firstName']; ?></em></small>

          </div>


        <?php if(!empty($_SESSION)):?>
            <!-- Logged in and owner of the post -->
            <?php if(strcmp($_SESSION['username'],$author[0]['username'])==0):?>
                  <!-- Edit and Delete -->
                <form class="mt-3" style="display: inline-block;" method="GET" action="edit_post.php">
                    <input type="hidden" name=id value="<?php echo $postId?>" >
                    <button class="btn btn-outline-primary btn-sm"  type="submit">Edit</button>
                </form>

                <form  style="display: inline-block;" method="POST" action="delete_post.php">
                    <input type="hidden" name=id value="<?php echo $postId?>" >
                    <button class="btn btn-outline-danger btn-sm"  type="submit">Delete</button>
                </form>

            <!-- Any logged in user -->
            <?php else:?>
                <div class="d-flex">
                <!-- Comment -->
                    <form style="margin-right: 60px;" action="../user/reaction.php" method="POST" class="mt-5" class="mb-3">
                        <label for="exampleFormControlTextarea1" class="form-label">Your thoughts:</label>
                        <input type="hidden" name=id value="<?php echo $postId; ?>" >
                        <textarea class="form-control" name="comment" id="exampleFormControlTextarea1" rows="3" placeholder="Your comments" required></textarea>
                        <button type="submit" name="comment_btn" class="btn btn-dark mt-2">Comment</button>
                    </form>
                    <!-- Subscribe -->

                    <form class="p-3" action="../user/reaction.php"  method="post">
                        <label class="mt-5 mb-2" for="exampleFormControlInput1" class="form-label">Subscribe to Our Newsletters:</label>
                        <input type="hidden" name=id value="<?php echo $postId; ?>" >
                        <input type="email" name="subscriber" class="form-control" id="exampleFormControlInput1" placeholder="example.gmail.com" required>
                        <button style="margin-top: 40px;" type="submit"name="subscriber_btn"  class="btn btn-dark">Subscribe</button>

                    </form>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <!-- User Not yet logged in -->
            <div class="d-flex">
              <!-- Comment -->
                <form style="margin-right: 60px;" action="../user/register.php" method="GET" class="mt-5" class="mb-3">
                    <label for="exampleFormControlTextarea1" class="form-label">Your thoughts:</label>
                    <textarea class="form-control" name="comment" id="exampleFormControlTextarea1" rows="3" placeholder="Your comments" required></textarea>
                    <button type="submit" name="comment_btn" class="btn btn-dark mt-2">Comment</button>
                </form>
                <!-- Subscribe -->

                <form class="p-3" action="../user/reaction.php"  method="post">
                    <label class="mt-5 mb-2" for="exampleFormControlInput1" class="form-label">Subscribe to Our Newsletters:</label>
                    <input type="hidden" name=id value="<?php echo $postId; ?>" >
                    <input type="email" name="subscriber" class="form-control" id="exampleFormControlInput1" placeholder="example.gmail.com" required>
                    <button style="margin-top: 40px;" type="submit"name="subscriber_btn"  class="btn btn-dark">Subscribe</button>

                </form>
            </div>

        <?php endif; ?>


        <div>
        <?php if(!empty($_SESSION)):?>
            <!-- Logged in and owner of the post -->
            <?php if(strcmp($_SESSION['username'],$author[0]['username'])==0): ?>


                <p class="text-center" style="text-decoration: underline;">More of Your Articles </p>
                <?php foreach($sameAuthor as $detail): ?>
                    <?php if($detail['id']!= $id): ?>
                        <h6><?php echo $detail['title']; ?></h6> <small>
                        <p><?php  echo substr($detail['content'], 0,50); ?>...</p>
                        <form style="display: inline-block;" method="POST" action="post_detail.php">
                            <input type="hidden" name=id value="<?php echo $detail['id']?>" >
                            <button class="btn btn-outline-primary btn-sm"  type="submit">Read More</button>
                        </form> <hr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <!-- Others -->
            <?php else: ?>
                <p class="text-center" style="text-decoration: underline;">More Articles By: <em> <?php echo $author[0]['firstName'] ,' '.$author[0]['lastName']; ?></em></small> </p>
                <?php foreach($sameAuthor as $detail): ?>
                    <?php if($detail['id']!= $id): ?>
                        <h6><?php echo $detail['title']; ?></h6> <small>
                        <p><?php  echo substr($detail['content'], 0,50); ?>...</p>
                        <form style="display: inline-block;" method="POST" action="post_detail.php">
                            <input type="hidden" name=id value="<?php echo $detail['id']?>" >
                            <button class="btn btn-outline-primary btn-sm"  type="submit">Read More</button>
                        </form> <hr>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        <?php else: ?>
            <p class="text-center" style="text-decoration: underline;">More Articles By: <em> <?php echo $author[0]['firstName'] ,' '.$author[0]['lastName']; ?></em></small> </p>

                <?php foreach($sameAuthor as $detail): ?>
                    <?php if($detail['id']!= $id): ?>
                        <h6><?php echo $detail['title']; ?></h6> <small>
                        <p><?php  echo substr($detail['content'], 0,50); ?>...</p>
                        <form style="display: inline-block;" method="POST" action="post_detail.php">
                            <input type="hidden" name=id value="<?php echo $detail['id']?>" >
                            <button class="btn btn-outline-primary btn-sm"  type="submit">Read More</button>
                        </form> <hr>
                    <?php endif; ?>
                <?php endforeach; ?>

         <?php endif; ?>


        </div>

</div>
<?php include '../includes/footer.php'?>
