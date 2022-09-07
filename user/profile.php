<?php
    include '../includes/header.php';
    include '../includes/database.php';
    include '../includes/errors.php';

    session_start();

  



    $session_username = $_SESSION['username'] ?? null;

    if(!$session_username){
        header("Location:../");
        exit;
    }

    $errors=[];


    $statement = $pdo->prepare('SELECT * FROM user WHERE username=:username');
    $statement->bindValue(':username',$session_username);
    $statement->execute();
    $user = $statement->fetchAll(PDO::FETCH_ASSOC);


    $firstName = $user[0]['firstName'];
    $lastName = $user[0]['lastName'];
    $username = $user[0]['username'];
    $email = $user[0]['email'];
    $profile = $user[0]['profile'];
    $id = $user[0]['id'];

    function random_string($n){
        $Characters = "1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
        $Str = '';

        for($i = 0; $i<=$n; $i++){
          $index = rand(0,strlen($Characters)-1);
          $Str .= $Characters[$index];

        }
        return $Str;
        }


    if($_SERVER['REQUEST_METHOD']==='POST'){
        $firstName = $_POST['first_name'];
        $lastName = $_POST['last_name'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $profile = $_FILES['profile'] ?? null;

        $statement=$pdo->prepare("SELECT * FROM user WHERE username = :username AND id != :id");
        $statement->bindValue(':username', $username);
        $statement->bindValue(':id', $id);
        $statement->execute();
        $same_username = $statement->fetchAll(PDO::FETCH_ASSOC);

        if(!$same_username){

            if($profile && $profile['tmp_name']!=''){
                if($profile['name'] != ''){

                    $imagePath = '../static/images/Profiles/'.random_string(8).'/'.$profile['name'];
                    mkdir(dirname($imagePath));
                    move_uploaded_file($profile['tmp_name'], $imagePath);
                }
            }

            if($imagePath != ''){
                $statement = $pdo->prepare("UPDATE user SET firstName=:firstName, lastName=:lastName, username=:username, profile=:profile WHERE id= :id");
                $statement->bindValue(':firstName',$firstName);
                $statement->bindValue(':lastName',$lastName);
                $statement->bindValue(':username',$username);
                $statement->bindValue(':profile',$imagePath);
                $statement->bindValue(':id',$id);
                $statement->execute();

              }else{
                $statement = $pdo->prepare("UPDATE user SET firstName=:firstName, lastName=:lastName, username=:username WHERE id= :id");
                $statement->bindValue(':firstName',$firstName);
                $statement->bindValue(':lastName',$lastName);
                $statement->bindValue(':username',$username);
                $statement->bindValue(':id',$id);
                $statement->execute();

              }

            $_SESSION['username']=$username;
            header("Location:../index.php");

        }else{
            $errors[]="Username Already Exists";
        }


      }


?>

<section class="vh-100">
  <div class="mask d-flex align-items-center h-100 gradient-custom-3">
    <div class="container h-100">
      <div class="row d-flex justify-content-center align-items-center h-100">
        <div class="col-12 col-md-9 col-lg-7 col-xl-6">

          <a class="m-2" href="../index.php">
                <i class="fas fa-arrow-left"></i>
            </a>
            <div class="card-body pb-1">
              <h3 class="text-center mb-1">Update Profile</h3>
              <?php foreach ($errors as $error): ?>
                  <div class="alert alert-danger" role="alert">
                    <p> <?php echo $error; ?> </p>
                  </div>

                <?php endforeach; ?>


              <form method="POST" enctype="multipart/form-data">


                <div class="form-outline mb-3 ">

                  <input type="text" name="first_name" class="form-control form-control-sm " value="<?php echo $firstName; ?>" required />

                </div>

                <div class="form-outline mb-3">

                  <input type="text" name="last_name" class="form-control form-control-sm" value="<?php echo $lastName; ?>" required/>

                </div>

                <div class="form-outline mb-3">

                  <input type="text" name="username" class="form-control form-control-sm" value="<?php echo $username; ?>" required/>

                </div>

                <div class="form-outline mb-3">

                  <input type="email" name="email" class="form-control form-control-sm" value="<?php echo $email; ?>" required/>

                </div>


                <div class="form-outline mb-3">
                  <label class="form-label" for="password2">Profile: Current(<?php echo $profile; ?>)</label>
                  <input type="file" name="profile" class="form-control form-control-sm" />

                </div>


                <div class="text-center text-lg-start mt-4 pt-2">
                  <button type="submit" class="btn btn-primary btn-sm"
                    style="padding-left: 2.5rem; padding-right: 2.5rem;">Update</button>

                </div>

              </form>

            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
