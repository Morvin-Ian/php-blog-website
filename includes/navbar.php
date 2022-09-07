<?php

    include 'header.php';
    session_start();

    if(isset($_SESSION['username'])){

      $now = time();
      if($now > $_SESSION['expire']){
        session_destroy();
        header("Location:user/login.php");

      }
    }

  
?>

<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container-fluid">
    <a class="navbar-brand" href="#">Brace Blog |</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
        <li class="nav-item">
          <a class="nav-link" href="#">News</a>
        </li>
        <li class="nav-item">
          <a class="nav-link" href="#">Recent Articles</a>
        </li>
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            More
          </a>
          <ul class="dropdown-menu">
            
            <li><a class="dropdown-item" href="#">Trending Articles</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="#">Contacts</a></li>
            <li><a class="dropdown-item" href="#">About Us</a></li>
          </ul>
        </li>
        <?php if(isset($_SESSION['username']) && isset($_SESSION['password'])):?>
          <li class="nav-item">
           <form style="display: inline-block;" method="GET" action="user/profile.php">
                <input type="hidden" name=username value="<?php echo $_SESSION['username']; ?>" >
                <button style="margin-left:650px; background-color:transparent; border:none;"  class="nav-link"  type="submit"><i class="fas fa-user-circle"></i>  <?php echo $_SESSION['username']?></button>
            </form>
           
          </li>
          <li class="nav-item">
            <a style="margin-left:10px;"  class="nav-link" href="user/logout.php"><i class="fas fa-arrow-right"></i> Logout</a>
         </li>
        <?php else:?>
          <li class="nav-item">
            <a style="margin-left:650px;" class="nav-link" href="user/register.php"><i class="fas fa-user"></i> Register</a>
          </li>
          <li class="nav-item">
            <a style="margin-left:10px;" class="nav-link" href="user/login.php"> <i class="fas fa-arrow-right"></i> Login</a>
          </li>
        <?php endif; ?>
        

   
     
        
      </ul>
     
    </div>
  </div>
</nav>
</body>