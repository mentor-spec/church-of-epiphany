<?php
if($_SERVER['REQUEST_METHOD'] == 'POST'){
  $name = htmlspecialchars($_POST['name']);
  $email = htmlspecialchars($_POST['email']);
  $phone = htmlspecialchars($_POST['phone']);
  $category = htmlspecialchars($_POST['category']);
  $message = htmlspecialchars($_POST['message']);

  $to = "sopurupromise2@gmail.com"; // Replace with your church email
  $subject = "New Prayer Request from $name";
  $body = "Name: $name\nEmail: $email\nPhone: $phone\nCategory: $category\n\nMessage:\n$message";
  $headers = "From: $email";

  if(mail($to, $subject, $body, $headers)){
    echo "success";
  } else {
    echo "failed";
  }
}
?>
