<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

include 'components/wishlist_cart.php';

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>Search Page</title>
   
   <link rel="stylesheet" href="css/font_awesome/css/all.min.css">
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="search-form">
   <form action="" method="GET">
      <input type="text" name="search_box" placeholder="Search here..." maxlength="100" class="box" required value="<?php echo isset($_GET['search_box']) ? htmlspecialchars($_GET['search_box']) : ''; ?>">
      <button type="submit" class="fas fa-search" name="search_btn"></button>

      
      <label for="sort">Sort Items:</label>
      <select name="sort" id="sort" onchange="this.form.submit()">
         <option value="name" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'name'){echo 'selected';} ?>>Sort By Name</option>
         <option value="price_asc" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'price_asc'){echo 'selected';} ?>>Sort By Price (Low to High)</option>
         <option value="price_desc" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'price_desc'){echo 'selected';} ?>>Sort By Price (High to Low)</option>
         <option value="rating" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'rating'){echo 'selected';} ?>>Sort By Rating</option>
         <option value="demand" <?php if(isset($_GET['sort']) && $_GET['sort'] == 'demand'){echo 'selected';} ?>>Sort By Demand</option>
      </select>
   </form>
</section>

<section class="products" style="padding-top: 0; min-height:100vh;">

   <div class="box-container">

   <?php
  
   if(isset($_GET['search_box']) || isset($_GET['search_btn'])){

      
      $search_box = htmlspecialchars($_GET['search_box']);

      
      $order_by = "ORDER BY name ASC";

      
      if(isset($_GET['sort'])){
         $sort_option = $_GET['sort'];
         switch ($sort_option) {
            case 'price_asc':
               $order_by = "ORDER BY price ASC";
               break;
            case 'price_desc':
               $order_by = "ORDER BY price DESC";
               break;
            case 'rating':
               $order_by = "ORDER BY rating DESC";
               break;
            case 'demand':
               $order_by = "ORDER BY demand DESC"; 
               break;
            case 'name':
            default:
               $order_by = "ORDER BY name ASC";
               break;
         }
      }

     
      $select_products = $conn->prepare("SELECT * FROM `products` WHERE name LIKE :search_query $order_by");
      $select_products->execute(['search_query' => "%{$search_box}%"]);

      
      if($select_products->rowCount() > 0){
         while($fetch_product = $select_products->fetch(PDO::FETCH_ASSOC)){
   ?>
   <form action="" method="post" class="box">
      <input type="hidden" name="pid" value="<?= $fetch_product['id']; ?>">
      <input type="hidden" name="name" value="<?= $fetch_product['name']; ?>">
      <input type="hidden" name="price" value="<?= $fetch_product['price']; ?>">
      <input type="hidden" name="image" value="<?= $fetch_product['image_01']; ?>">
      <button class="fas fa-heart" type="submit" name="add_to_wishlist"></button>
      <a href="product_quick_view.php?pid=<?= $fetch_product['id']; ?>" class="fas fa-eye"></a>
      <img src="project_images/<?= $fetch_product['image_01']; ?>" alt="">
      <div class="name"><?= $fetch_product['name']; ?></div>
      <div class="flex">
         <div class="price"><span>Rs.</span><?= $fetch_product['price']; ?><span>/-</span></div>
         <input type="number" name="qty" class="qty" min="1" max="99" onkeypress="if(this.value.length == 2) return false;" value="1">
      </div>
      <input type="submit" value="Add to Cart" class="btn" name="add_to_cart">
   </form>
   <?php
         }
      }else{
         echo '<p class="empty">No products found!</p>';
      }
   }
   ?>

   </div>

</section>

<?php include 'components/footer.php'; ?>

<script src="js/script.js"></script>

</body>
</html>
