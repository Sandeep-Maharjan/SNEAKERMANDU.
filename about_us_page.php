<?php

include 'components/connect.php';

session_start();

if(isset($_SESSION['user_id'])){
   $user_id = $_SESSION['user_id'];
}else{
   $user_id = '';
};

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>about</title>

   <link rel="stylesheet" href="css/swiper-bundle.min.css" />
   
  
   <link rel="stylesheet" href="css/font_awesome/css/all.min.css">

  
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php include 'components/user_header.php'; ?>

<section class="about">

   <div class="row">

      <div class="image">
         <img src="images/about-img.jpg" alt="">
      </div>

      <div class="content">
         <h3>why choose us?</h3>
         <p>Sneakers and streetwear have roots that trace back to some of the most famous street artists, graffiti writers, muralists and designers of all time.With more than 2500 stores across the world, SNEAKERMANDU is the leading global athletic footwear and apparel retailer. Our close partnerships with the ultimate top brands mean that we will constantly provide you with the best, most exclusive ranges of products there is. At SNEAKERMANDU, we live sneakers, we breathe sneakers, we dream sneakers… Sneakers are all we think about and nothing makes us more proud than being able to keep our followers up to date with the latest trends.Stay tuned and keep your sneaker lifestyle alive and kicking with Foot Locker!</p>
         <a href="contact_us_page.php" class="btn">contact us</a>
      </div>

   </div>

</section>

<section class="reviews">
   
   <h1 class="heading">client's reviews</h1>

   <div class="swiper reviews-slider">

   <div class="swiper-wrapper">

      <div class="swiper-slide slide">
         <img src="images/pic-a.jpg" alt="">
         <p>Best Quality sneakers. Would come next time too....</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
         </div>
         <h3>Sandeep Maharjan</h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/pic-b.jpg" alt="">
         <p>Best sneaker site.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Dev Aashish Gole</h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/pic-c.jpg" alt="">
         <p>Good choices and appreciable prices.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Aayuz Shahi</h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/pic-d.jpg" alt="">
         <p>I Loved it...Would come next time too.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Aashish Gole</h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/pic-e.jpg" alt="">
         <p>Loved it...</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star-half-alt"></i>
         </div>
         <h3>Basanta Adhikari</h3>
      </div>

      <div class="swiper-slide slide">
         <img src="images/pic-f.jpg" alt="">
         <p>Good Quality.... I would give it 5 stars.</p>
         <div class="stars">
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
            <i class="fas fa-star"></i>
         </div>
         <h3>Bikesh Shakya</h3>
      </div>

   </div>

   <div class="swiper-pagination"></div>

   </div>

</section>









<?php include 'components/footer.php'; ?>

<script src="js/swiper-bundle.min.js"></script>

<script src="js/script.js"></script>

<script>

var swiper = new Swiper(".reviews-slider", {
   loop:true,
   spaceBetween: 20,
   pagination: {
      el: ".swiper-pagination",
      clickable:true,
   },
   breakpoints: {
      0: {
        slidesPerView:1,
      },
      768: {
        slidesPerView: 2,
      },
      991: {
        slidesPerView: 3,
      },
   },
});

</script>

</body>
</html>