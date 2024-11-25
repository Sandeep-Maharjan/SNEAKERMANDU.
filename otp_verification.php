<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'components/connect.php';

session_start();

if(!isset($_SESSION['email']) || !isset($_SESSION['otp'])){
   header('location:user_login.php');
   exit();
}

if(isset($_POST['submit'])){
   $entered_otp = $_POST['otp'];
   $email = $_SESSION['email'];

   
   $stmt = $conn->prepare("SELECT otp_code FROM user_otp WHERE email = ? AND expires_at > NOW() ORDER BY created_at DESC LIMIT 1");
   $stmt->execute([$email]);
   $row = $stmt->fetch(PDO::FETCH_ASSOC);

   if($stmt->rowCount() > 0 && $row['otp_code'] == $entered_otp){
     
      echo "OTP Verified Successfully!";
      
      $delete_otp = $conn->prepare("DELETE FROM user_otp WHERE email = ?");
      $delete_otp->execute([$email]);

     
      header('location:homepage.php');
      exit();
   } else {
      echo "Invalid or expired OTP!";
   }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>OTP Verification</title>
   <style>
      * {
         margin: 0;
         padding: 0;
         box-sizing: border-box;
         font-family: Arial, sans-serif;
      }

      body {
         display: flex;
         justify-content: center;
         align-items: center;
         height: 100vh;
         background-color: #f0f0f0;
      }

      form {
         background-color: #fff;
         padding: 2rem;
         border-radius: 8px;
         box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
         max-width: 400px;
         width: 100%;
         text-align: center;
         color: Black;
      }

      h3 {
         margin-bottom: 1rem;
         font-size: 1.5rem;
         color: Black;
         
      }

      .box {
         width: 100%;
         padding: 0.8rem;
         margin-bottom: 1rem;
         border: 1px solid #ccc;
         border-radius: 4px;
         font-size: 1rem;
         outline: none;
         transition: border 0.3s ease;
         color: White;
      }

      .box:focus {
         border-color: #555;
      }

      .btn {
         width: 100%;
         padding: 0.8rem;
         border: none;
         border-radius: 4px;
         font-size: 1rem;
         background-color: Orange; 
         color: #fff;
         cursor: pointer;
         transition: background-color 0.3s ease;
      }

      .btn:hover {
         background-color: #888; 
      }
   </style>
</head>
<body>

<form action="" method="post">
   <h3>Enter OTP</h3>
   <input type="text" name="otp" required placeholder="Enter your OTP" maxlength="6" class="box">
   <input type="submit" value="Verify OTP" class="btn" name="submit">
</form>

</body>
</html>
