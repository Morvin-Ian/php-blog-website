<?php

  include "../includes/database.php";
  include "../includes/errors.php";

  use PHPMailer\PHPMailer\PHPMailer;
  use PHPMailer\PHPMailer\Exception;

  require '../vendor/autoload.php';

  $subject = "Newsletter Subscription";
  $body = "<strong><em><p>Brace Blogs</p></em></strong>
          <p>We are grateful you subscribed to our blogs </p>
          <p>You are lucky because we also have articles about Semen retention </p>
  ";
  if($_SERVER['REQUEST_METHOD']==='POST'){

    $receiver = $_POST["subscriber"];

    try{
      $mail = new PHPMailer(true);
      $mail->SMTPDebug = 2;
      $mail->isSMTP();
      $mail->Host = 'smtp.gmail.com';
      $mail->SMTPAuth = true;
      $mail->Username ="example@gmail.com";
      $mail->Password ="*******";
      $mail->SMTPSecure = 'ssl';
      $mail->Port = 465;
      $mail->isHTML(true);
      $mail->setFrom("example@gmail.com");
      $mail->addAddress($receiver);
      $mail->Subject = $subject;
      $mail->Body = $body;
      $mail->send();

      header("Location:../");


  }
    catch (Exception $e) {
      header("Location:../");
  }
    
  }

    
