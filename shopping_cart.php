<?php

include 'components/connect.php';

session_start();

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
} else {
    $user_id = '';
    header('location:user_login.php');
};

if (isset($_POST['add_to_cart'])) {

    $pid = $_POST['pid'];
    $pid = filter_var($pid, FILTER_SANITIZE_STRING);
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_NUMBER_INT);

   
    $check_stock = $conn->prepare("SELECT stock FROM `products` WHERE id = ?");
    $check_stock->execute([$pid]);
    $stock = $check_stock->fetchColumn();

    if ($stock < $qty) {
        $message[] = 'Not enough stock available!';
    } else {
        
        $update_stock = $conn->prepare("UPDATE `products` SET stock = stock - ? WHERE id = ?");
        $update_stock->execute([$qty, $pid]);

       
        $add_to_cart = $conn->prepare("INSERT INTO `cart`(user_id, pid, quantity) VALUES(?,?,?)");
        $add_to_cart->execute([$user_id, $pid, $qty]);
        $message[] = 'Added to cart!';
    }
}

if (isset($_POST['delete'])) {
    $cart_id = $_POST['cart_id'];
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE id = ?");
    $delete_cart_item->execute([$cart_id]);
}

if (isset($_GET['delete_all'])) {
    $delete_cart_item = $conn->prepare("DELETE FROM `cart` WHERE user_id = ?");
    $delete_cart_item->execute([$user_id]);
    header('location:shopping_cart.php');
}


if (isset($_POST['update_qty'])) {
    $cart_id = $_POST['cart_id'];
    $qty = $_POST['qty'];
    $qty = filter_var($qty, FILTER_SANITIZE_STRING);

    
    $get_cart_item = $conn->prepare("SELECT pid FROM `cart` WHERE id = ?");
    $get_cart_item->execute([$cart_id]);
    $pid = $get_cart_item->fetchColumn();

    
    $check_stock = $conn->prepare("SELECT stock FROM `products` WHERE id = ?");
    $check_stock->execute([$pid]);
    $stock = $check_stock->fetchColumn();

    if ($stock < $qty) {
        $message[] = 'Not enough stock available!';
    } else {
        
        $update_qty = $conn->prepare("UPDATE `cart` SET quantity = ? WHERE id = ?");
        $update_qty->execute([$qty, $cart_id]);
        $message[] = 'Cart quantity updated';
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>shopping cart</title>
   
   <link rel="stylesheet" href="css/font_awesome/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="products shopping-cart">

   <h3 class="heading">shopping cart</h3>

   <div class="box-container">

   <?php
      $grand_total = 0;
      $select_cart = $conn->prepare("SELECT * FROM `cart` WHERE user_id = ?");
      $select_cart->execute([$user_id]);
      if($select_cart->rowCount() > 0){
         while($fetch_cart = $select_cart->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="cart_id" value="<?= $fetch_cart['id']; ?>">
      <a href="product_quick_view.php?pid=<?= $fetch_cart['pid']; ?>" class="fas fa-eye"></a>
      <img src="project_images/<?= $fetch_cart['image']; ?>" alt="">
      <div class="name"><?= $fetch_cart['name']; ?></div>
      <div class="flex">
         <div class="price">Rs.<?= $fetch_cart['price']; ?>/-</div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="<?= $fetch_cart['quantity']; ?>">
         <button type="submit" class="fas fa-edit" name="update_qty"></button>
      </div>
      <div class="sub-total"> sub total : <span>Rs.<?= $sub_total = ($fetch_cart['price'] * $fetch_cart['quantity']); ?>/-</span> </div>
      <input type="submit" value="delete item" onclick="return confirm('delete this from cart?');" class="delete-btn" name="delete">
   </form>
   <?php
   $grand_total += $sub_total;
      }
   } else {
      echo '<p class="empty">your cart is empty</p>';
   }
   ?>
   </div>

   <div class="cart-total">
      <p>grand total : <span>Rs.<?= $grand_total; ?>/-</span></p>
      <a href="shop_page.php" class="option-btn">continue shopping</a>
      <a href="shopping_cart.php?delete_all" class="delete-btn <?= ($grand_total > 1)?'':'disabled'; ?>" onclick="return confirm('delete all from cart?');">delete all item</a>
      <a href="checkout_page.php" class="btn <?= ($grand_total > 1)?'':'disabled'; ?>">proceed to checkout</a>
   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
