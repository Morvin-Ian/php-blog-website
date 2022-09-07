<?php
    include 'includes/header.php';
    include 'includes/navbar.php';
    include 'includes/database.php';
    session_start();


    //search
    $search = $_GET['search'] ?? '';

    if($search){
        $statement = $pdo->prepare("SELECT * FROM post WHERE title LIKE :keyword OR content LIKE :keyword");
        $statement->bindValue(':keyword', "%$search%");
        $statement->execute();
        $posts = $statement->fetchAll(PDO::FETCH_ASSOC);

    }


    // pagination
    if(isset($_GET['page']) && $_GET['page'] != ''){
        $page = $_GET['page'];
    }else{
        $page = 1;
    }


    // $records_per_page = 3;
    // $offset = ($page-1)* $records_per_page;
    // $previous_page = $page-1;
    // $next_page = $page+1;
    // $adjacents - "2";

    $statement = $pdo->prepare("SELECT * FROM post ORDER BY createdAt DESC");
    $statement->execute();
    $posts = $statement->fetchAll(PDO::FETCH_ASSOC);


?>


<?php if(isset($_SESSION['username']) && isset($_SESSION['password'])):?>
    <div class="m-4 d-flex" >
        <a style="text-decoration: none; color: black;" href="blog_post/create_post.php">
                <i class="fas fa-plus"></i> <small class="dislpay-6">Create Post</small>
        </a>

        <form style="margin-left:20%;" class="d-flex" role="search">
            <input class="form-control me-2" name="search" type="search" placeholder="Search Post" aria-label="Search">
            <button class="btn btn-outline-dark" type="submit">Search</button>
        </form>
</div>
<?php else: ?>
    <div class="m-3 d-flex">
        <form style="margin-left:20%;" class="d-flex" role="search">
            <input class="form-control me-2" name="search" type="search" placeholder="Search Post" aria-label="Search">
            <button class="btn btn-outline-dark" type="submit">Search</button>
        </form>
    </div>

<?php endif; ?>

<div class="container">
    <?php if(!$posts): ?>
        <h5>No Blogs Available</h5>
    <?php else: ?>

        <?php foreach($posts as $post): ?>

            <h6><?php echo $post['title']; ?></h6>
            <small>Updated on: <?php echo $post['updatedAt']; ?></small> <br>
            <form style="display: inline-block;" method="POST" action="blog_post/post_detail.php">
                <input type="hidden" name=id value="<?php echo $post['id']?>" >
                <input type="hidden" name=views value="1" >
                <button class="btn btn-dark btn-sm" style="padding: 0.4rem; margin-top:10px;" type="submit">Read More</button>
            </form>

            <hr>

        <?php endforeach; ?>
    <?php endif; ?>
</div>
