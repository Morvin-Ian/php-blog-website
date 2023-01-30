<?php
    include 'includes/header.php';
    include 'includes/navbar.php';
    include 'includes/database.php';
    // session_start();

    //search
    $search = $_GET['search'] ?? '';

    // pagination

    // Calculate Total pages
   $perPage = 3;
   $stmt = $pdo->prepare('SELECT count(*) FROM post');
   $stmt->execute();
   $total_results = $stmt->fetchColumn();
   $total_pages = ceil($total_results / $perPage);

   // Current page
   $page = isset($_GET['page']) ? $_GET['page'] : 1;
   $starting_limit = ($page - 1) * $perPage;

    if($search){
        $statement = $pdo->prepare("SELECT * FROM post WHERE title LIKE :keyword OR content LIKE :keyword");
        $statement->bindValue(':keyword', "%$search%");
        $statement->execute();
        $posts = $statement->fetchAll(PDO::FETCH_ASSOC);

    } elseif ($page){
         // Query to fetch users
         $statement = $pdo->prepare("SELECT * FROM post ORDER BY id DESC LIMIT $starting_limit,$perPage");
         $statement->execute();

         // Fetch all users for current page
         $posts = $statement->fetchAll(PDO::FETCH_ASSOC);

    } else{
          $statement = $pdo->prepare("SELECT * FROM post ORDER BY createdAt DESC");
          $statement->execute();
          $posts = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
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
<div class="d-flex">

  <div  class="ms-4">
    <h5>Topics</h5>
    <ul style="line-height:40px;">

      <li>Sports</li>
      <li>News</li>
      <li>Computer Programming</li>
      <li>Cyberseurity</li>
      <li>Tutorials</li>
      <li>Computers</li>
      <li>Cyber Terrorismy</li>
    </ul>

  </div>

  <div style="width:50%;" class="container-fluid" >
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

            <nav aria-label="Page navigation example">
              <ul class="pagination">

                  <?php for ($page = 1; $page <= $total_pages ; $page++):?>

                    <li class="page-item">
                      <a href='<?php echo "?page=$page"; ?>' class="page-link">  <?php  echo $page; ?></a>
                    </li>

                  <?php endfor; ?>


              </ul>
          </nav>






      <?php endif; ?>
  </div>
  <div  class="me-4">
    <h5>Recent Activities</h5>
    <ul>

      <li>Sports</li> <hr>
      <li>News</li> <hr>
      <li>Computer Programming</li> <hr>
      <li>Cyberseurity</li> <hr>
      <li>Tutorials</li>

    </ul>

  </div>

</div>
