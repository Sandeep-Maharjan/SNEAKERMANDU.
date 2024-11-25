<?php

require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';
require 'PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
   $user_id = $_SESSION['user_id'];
} else {
   $user_id = '';
   header('location:user_login.php');
};

if (isset($_POST['order'])) {

   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $method = $_POST['method'];
   $method = filter_var($method, FILTER_SANITIZE_STRING);
   $address = $_POST['Street'] . ', ' . $_POST['City'];
   $address = filter_var($address, FILTER_SANITIZE_STRING);
   $total_products = $_POST['total_products'];
   $total_price = $_POST['total_price'];

   $check_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
   $check_cart->execute([$user_id]);

   if ($check_cart->rowCount() > 0) {

      $insert_order = $conn->prepare("INSERT INTO `orders`(user_id, name, number, email, method, address, total_products, total_price) VALUES(?,?,?,?,?,?,?,?)");
      $insert_order->execute([$user_id, $name, $number, $email, $method, $address, $total_products, $total_price]);

      $cart_items = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $cart_items->execute([$user_id]);

      while ($item = $cart_items->fetch(PDO::FETCH_ASSOC)) {
         $pid = $item['pid'];
         $quantity = $item['quantity'];

         $select_stock = $conn->prepare("SELECT stock, name FROM products WHERE id = ?");
         $select_stock->execute([$pid]);
         $fetch_stock = $select_stock->fetch(PDO::FETCH_ASSOC);
         $new_stock = $fetch_stock['stock'] - $quantity;

         $update_stock = $conn->prepare("UPDATE products SET stock = stock - ? WHERE id = ?");
         $update_stock->execute([$quantity, $pid]);

         if ($new_stock < 5) {
            $mail = new PHPMailer(true);
            try {
               $mail->isSMTP();
               $mail->Host = 'smtp.gmail.com'; 
               $mail->SMTPAuth = true;
               $mail->Username = 'sneakersahead0@gmail.com'; 
               $mail->Password = 'gytn vhml ppgz nrsn';   
               $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
               $mail->Port = 587;

               $mail->setFrom('your-email@gmail.com', 'Admin');
               $mail->addAddress('sneakersahead0@gmail.com'); 

               $mail->isHTML(true);
               $mail->Subject = 'Restock Alert';
               $mail->Body = 'The product ' . $fetch_stock['name'] . ' (ID: ' . $pid . ') is low in stock. Only ' . $new_stock . ' items left.';

               $mail->send();
            } catch (Exception $e) {
               echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
            }
         }
      }

      $delete_cart = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
      $delete_cart->execute([$user_id]);

      $message[] = 'Order placed successfully!';
   } else {
      $message[] = 'Your cart is empty';
   }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Checkout</title>
   
   <link rel="stylesheet" href="css/font_awesome/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">
</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="checkout-orders">

   <form action="" method="POST">

   <h3>Your Orders</h3>

      <div class="display-orders">
      <?php
         $grand_total = 0;
         $cart_items = ''; 
         $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
         $select_cart->execute([$user_id]);
         if ($select_cart->rowCount() > 0) {
            while ($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)) {
               $cart_items .= $fetch_cart['name'] . ' (' . $fetch_cart['price'] . ' x ' . $fetch_cart['quantity'] . '), '; 
               $grand_total += ($fetch_cart['price'] * $fetch_cart['quantity']);
      ?>
         <p> <?= $fetch_cart['name']; ?> <span>(<?= 'Rs.' . $fetch_cart['price'] . '/- x ' . $fetch_cart['quantity']; ?>)</span> </p>
      <?php
            }
            $cart_items = rtrim($cart_items, ', ');
         } else {
            echo '<p class="empty">Your cart is empty!</p>';
         }
      ?>
         <input type="hidden" name="total_products" value="<?= $cart_items; ?>"> 
         <input type="hidden" name="total_price" value="<?= $grand_total; ?>">
         <div class="grand-total">Grand total : <span>Rs.<?= $grand_total; ?>/-</span></div>
      </div>

      <h3>Place your order</h3>

      <div class="flex">
         <div class="inputBox">
            <span>Your Name :</span>
            <input type="text" name="name" placeholder="Enter your full name" class="box" maxlength="20" required>
         </div>
         <div class="inputBox">
            <span>Your Number :</span>
            <input type="number" name="number" placeholder="Enter your contact number (10 Digits)" class="box" min="0" max="9999999999" onkeypress="if(this.value.length == 10) return false;" required>
         </div>
         <div class="inputBox">
            <span>Your Email :</span>
            <input type="email" name="email" placeholder="Enter your email" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>Payment Method :</span>
            <select name="method" class="box" required>
               <option value="cash on delivery">Cash on Delivery</option>
               <option value="bank transfer">Bank Transfer</option>
               <option value="eSewa">eSewa</option>
               <option value="khalti">Khalti</option>
            </select>
         </div>
         <div class="inputBox">
            <span>Street Address :</span>
            <input type="text" name="Street" placeholder="e.g. Kumaripati" class="box" maxlength="50" required>
         </div>
         <div class="inputBox">
            <span>City :</span>
            <input type="text" name="City" placeholder="e.g. Lalitpur" class="box" maxlength="50" required>
         </div>
      </div>

      <input type="submit" name="order" class="btn <?= ($grand_total > 1) ? '' : 'disabled'; ?>" value="Place Order">

   </form>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
