<?php

include 'components/connect.php';


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'C:\xampp\htdocs\sneakermandu\PHPMailer\src\Exception.php';
require 'C:\xampp\htdocs\sneakermandu\PHPMailer\src\PHPMailer.php';
require 'C:\xampp\htdocs\sneakermandu\PHPMailer\src\SMTP.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
}

if(isset($_POST['submit'])){

   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $pass = sha1($_POST['pass']);
   $pass = filter_var($pass, FILTER_SANITIZE_STRING);

   $select_user = $conn->prepare("SELECT * FROM `users` WHERE email = ? AND password = ?");
   $select_user->execute([$email, $pass]);
   $row = $select_user->fetch(PDO::FETCH_ASSOC);

   if($select_user->rowCount() > 0){
      $_SESSION['user_id'] = $row['id'];
      $_SESSION['email'] = $email;

      
      $otp = rand(100000, 999999);
      $_SESSION['otp'] = $otp;

      
      $insert_otp = $conn->prepare("INSERT INTO user_otp (email, otp_code, expires_at) VALUES (?, ?, DATE_ADD(NOW(), INTERVAL 10 MINUTE))");
      $insert_otp->execute([$email, $otp]);

      $mail = new PHPMailer(true);
      
      try {
         
         $mail->isSMTP();
         $mail->Host = 'smtp.gmail.com';  
         $mail->SMTPAuth = true;
         $mail->Username = 'sneakersahead0@gmail.com'; 
         $mail->Password = 'gytn vhml ppgz nrsn';   
         $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
         $mail->Port = 587;
      
        
         $mail->setFrom('your-gmail-email@gmail.com', 'Your Name');
         $mail->addAddress($email); 
      
        
         $mail->isHTML(true);
         $mail->Subject = 'Your OTP Code';
         $mail->Body = 'Your OTP code is: ' . $otp;
      
         $mail->send();
         echo 'OTP has been sent to your email!';
         header('location:otp_verification.php');
         exit();
      } catch (Exception $e) {
         echo "Failed to send OTP! Mailer Error: {$mail->ErrorInfo}";
        }
      
   } else {
            $message[] = 'Incorrect username or password!';
    }
}

?>


<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Login</title>
   
   
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="form-container">
   <form action="" method="post">
      <h3>Login Now</h3>
      <input type="email" name="email" required placeholder="Enter your email" maxlength="50" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="password" name="pass" required placeholder="Enter your password" maxlength="20" class="box" oninput="this.value = this.value.replace(/\s/g, '')">
      <input type="submit" value="Login Now" class="btn" name="submit">
      <p>Don't have an account?</p>
      <a href="user_registration.php" class="option-btn">Register Now</a>
   </form>
</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
