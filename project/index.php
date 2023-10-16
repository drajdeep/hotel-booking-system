<?php

include 'components/connect.php';

if(isset($_COOKIE['user_id'])){
   $user_id = $_COOKIE['user_id'];
}else{
   setcookie('user_id', create_unique_id(), time() + 60*60*24*30, '/');
   header('location:index.php');
}

if(isset($_POST['check']))
{

   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
      $total_rooms += $fetch_bookings['rooms'];
   }

   // if the hotel has total 30 rooms 
   if($total_rooms >= 30){
      $warning_msg[] = 'rooms are not available';
   }else{
      $success_msg[] = 'rooms are available';
   }

}

if(isset($_POST['book']))
{

   $booking_id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $rooms = $_POST['rooms'];
   $rooms = filter_var($rooms, FILTER_SANITIZE_STRING);
   $check_in = $_POST['check_in'];
   $check_in = filter_var($check_in, FILTER_SANITIZE_STRING);
   $check_out = $_POST['check_out'];
   $check_out = filter_var($check_out, FILTER_SANITIZE_STRING);
   $adults = $_POST['adults'];
   $adults = filter_var($adults, FILTER_SANITIZE_STRING);
   $childs = $_POST['childs'];
   $childs = filter_var($childs, FILTER_SANITIZE_STRING);

   $total_rooms = 0;

   $check_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE check_in = ?");
   $check_bookings->execute([$check_in]);

   while($fetch_bookings = $check_bookings->fetch(PDO::FETCH_ASSOC)){
      $total_rooms += $fetch_bookings['rooms'];
   }

   if($total_rooms >= 30){
      $warning_msg[] = 'rooms are not available';
   }else{

      $verify_bookings = $conn->prepare("SELECT * FROM `bookings` WHERE user_id = ? AND name = ? AND email = ? AND number = ? AND rooms = ? AND check_in = ? AND check_out = ? AND adults = ? AND childs = ?");
      $verify_bookings->execute([$user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);

      if($verify_bookings->rowCount() > 0){
         $warning_msg[] = 'room booked alredy!';
      }else{
         $book_room = $conn->prepare("INSERT INTO `bookings`(booking_id, user_id, name, email, number, rooms, check_in, check_out, adults, childs) VALUES(?,?,?,?,?,?,?,?,?,?)");
         $book_room->execute([$booking_id, $user_id, $name, $email, $number, $rooms, $check_in, $check_out, $adults, $childs]);
         $success_msg[] = 'room booked successfully!';
      }

   }

}

if(isset($_POST['send'])){

   $id = create_unique_id();
   $name = $_POST['name'];
   $name = filter_var($name, FILTER_SANITIZE_STRING);
   $email = $_POST['email'];
   $email = filter_var($email, FILTER_SANITIZE_STRING);
   $number = $_POST['number'];
   $number = filter_var($number, FILTER_SANITIZE_STRING);
   $message = $_POST['message'];
   $message = filter_var($message, FILTER_SANITIZE_STRING);

   $verify_message = $conn->prepare("SELECT * FROM `messages` WHERE name = ? AND email = ? AND number = ? AND message = ?");
   $verify_message->execute([$name, $email, $number, $message]);

   if($verify_message->rowCount() > 0){
      $warning_msg[] = 'message sent already!';
   }else{
      $insert_message = $conn->prepare("INSERT INTO `messages`(id, name, email, number, message) VALUES(?,?,?,?,?)");
      $insert_message->execute([$id, $name, $email, $number, $message]);
      $success_msg[] = 'message send successfully!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>HOME</title>

   <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.css" />

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.min.css">
<!-- ICON NEEDS FONT AWESOME FOR CHEVRON UP ICON -->
<!-- <link href="//netdna.bootstrapcdn.com/font-awesome/3.2.1/css/font-awesome.css" rel="stylesheet"> -->
   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php include 'components/user_header.php'; ?>
<!-- Return to Top -->
<button id="scrollToTopBtn" title="Go to top"><span>&#8593;</span></button>



<!-- home section starts  -->

<section class="home" id="home">

   <div class="swiper home-slider">

      <div class="swiper-wrapper">


         <div class="box swiper-slide">
            <img src="images/s1.jpg" alt="">
            <div class="flex">
               <h3>Serene View</h3>
               <a href="#contact" class="btn">contact us</a>
            </div>
         </div>

         <div class="box swiper-slide">
            <img src="images/r1.jpg" alt="">
            <div class="flex">
               <h3>Exquisite Suits</h3>
               <a href="#reservation" class="btn">make a reservation</a>
            </div>
         </div>

         
         <div class="box swiper-slide">
            <img src="images/hall.jpg" alt="">
            <div class="flex">
               <h3>luxurious halls</h3>
               <a href="#contact" class="btn">contact us</a>
            </div>
         </div>

         <div class="box swiper-slide">
            <img src="images/bar.jpg" alt="">
            <div class="flex">
               <h3>foods and drinks</h3>
               
            </div>
         </div>

         

      </div>

      <div class="swiper-button-next"></div>
      <div class="swiper-button-prev"></div>

   </div>

</section>

<!-- home section ends -->

<!-- availability section starts  -->

<section class="availability" id="availability">

   <form action="" method="post">
      <div class="flex">
         <div class="box">
            <p>Check In <span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>Check Out <span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>Adults <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1">1 adult</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>
         <div class="box">
            <p>Children <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="-">0 child</option>
               <option value="1">1 child</option>
               <option value="2">2 childs</option>
               <option value="3">3 childs</option>
               <option value="4">4 childs</option>
               <option value="5">5 childs</option>
               <option value="6">6 childs</option>
            </select>
         </div>
         <div class="box">
            <p>Rooms <span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1">1 room</option>
               <option value="2">2 rooms</option>
               <option value="3">3 rooms</option>
               <option value="4">4 rooms</option>
               <option value="5">5 rooms</option>
               <option value="6">6 rooms</option>
            </select>
         </div>
      </div>
      <input type="submit" value="check availability" name="check" class="btn">
   </form>

</section>

<!-- availability section ends -->

<!-- about section starts  -->

<section class="about" id="about">

   <div class="row">
      <div class="image">
         <img src="images/dining.jpg" alt="">
      </div>
      <div class="content">
         <h3>Culinary Delights</h3>
         <p>Savor the flavors of world-class cuisine at our restaurants. From gourmet dining to casual bites, we have it all.</p>
         <a href="#reservation" class="btn">make a reservation</a>
      </div>
   </div>

   <div class="row revers">
      <div class="image">
         <img src="images/lux.jpg" alt="">
      </div>
      <div class="content">
         <h3>Experience Luxury and Comfort</h3>
         <p>Begin your journey of relaxation and indulgence at Royal-Blu Haven Hotel, where every guest is treated like royalty.</p>
         <a href="#contact" class="btn">contact us</a>
      </div>
   </div>

   <div class="row">
      <div class="image">
         <img src="images/spa.jpg" alt="">
      </div>
      <div class="content">
         <h3>Unmatched Amenities</h3>
         <p>Indulge in our top-notch facilities, including a spa, fitness center, and more. Your wellness and comfort are our priorities.</p>
         <a href="#availability" class="btn">check availability</a>
      </div>
   </div>

</section>

<!-- about section ends -->

<!-- services section starts  -->

<section class="services">

   <div class="box-container">

   <div class="box">
         <img src="images/icon-2.png" alt="">
         <h3>outdoor dining</h3>
         <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p> -->
      </div>

      <div class="box">
         <img src="images/icon-1.png" alt="">
         <h3>food & drinks</h3>
         <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p> -->
      </div>

      <div class="box">
         <img src="images/icon-4.png" alt="">
         <h3>decorations</h3>
         <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p> -->
      </div>


      <div class="box">
         <img src="images/icon-3.png" alt="">
         <h3>serene view</h3>
         <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p> -->
      </div>

      <div class="box">
         <img src="images/icon-6.png" alt="">
         <h3>pool facility</h3>
         <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p> -->
      </div>

      <div class="box">
         <img src="images/icon-5.png" alt="">
         <h3>laundry</h3>
         <!-- <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Libero, sunt?</p> -->
      </div>

      
   </div>

</section>

<!-- services section ends -->

<!-- reservation section starts  -->

<section class="reservation" id="reservation">

   <form action="" method="post">
      <h3>make a reservation</h3>
      <div class="flex">
         <div class="box">
            <p>your name <span>*</span></p>
            <input type="text" name="name" maxlength="50" required placeholder="enter your name" class="input">
         </div>
         <div class="box">
            <p>your email <span>*</span></p>
            <input type="email" name="email" maxlength="50" required placeholder="enter your email" class="input">
         </div>
         <div class="box">
            <p>your number <span>*</span></p>
            <input type="number" name="number" maxlength="10" min="0" max="9999999999" required placeholder="enter your number" class="input">
         </div>
         <div class="box">
            <p>rooms <span>*</span></p>
            <select name="rooms" class="input" required>
               <option value="1" selected>1 room</option>
               <option value="2">2 rooms</option>
               <option value="3">3 rooms</option>
               <option value="4">4 rooms</option>
               <option value="5">5 rooms</option>
               <option value="6">6 rooms</option>
            </select>
         </div>
         <div class="box">
            <p>check in <span>*</span></p>
            <input type="date" name="check_in" class="input" required>
         </div>
         <div class="box">
            <p>check out <span>*</span></p>
            <input type="date" name="check_out" class="input" required>
         </div>
         <div class="box">
            <p>adults <span>*</span></p>
            <select name="adults" class="input" required>
               <option value="1" selected>1 adult</option>
               <option value="2">2 adults</option>
               <option value="3">3 adults</option>
               <option value="4">4 adults</option>
               <option value="5">5 adults</option>
               <option value="6">6 adults</option>
            </select>
         </div>
         <div class="box">
            <p>children <span>*</span></p>
            <select name="childs" class="input" required>
               <option value="0" selected>0 child</option>
               <option value="1">1 child</option>
               <option value="2">2 childs</option>
               <option value="3">3 childs</option>
               <option value="4">4 childs</option>
               <option value="5">5 childs</option>
               <option value="6">6 childs</option>
            </select>
         </div>
      </div>
      <input type="submit" value="book now" name="book" class="btn">
   </form>

</section>

<!-- reservation section ends -->

<!-- gallery section starts  -->

<section class="gallery" id="gallery">

   <div class="swiper gallery-slider">
      <div class="swiper-wrapper">
         <img src="images/gallery-img-2.webp" class="swiper-slide" alt="">
         <img src="images/gallery-img-1.jpg" class="swiper-slide" alt="">
         <img src="images/gallery-img-4.webp" class="swiper-slide" alt="">
         <img src="images/gallery-img-3.webp" class="swiper-slide" alt="">
         <img src="images/gallery-img-6.webp" class="swiper-slide" alt="">
         <img src="images/gallery-img-5.webp" class="swiper-slide" alt="">
         
      </div>
      <div class="swiper-pagination"></div>
   </div>

</section>

<!-- gallery section ends -->

<!-- contact section starts  -->

<section class="contact" id="contact">

   <div class="row">

      <form action="" method="post">
         <h3>send us message</h3>
         <input type="text" name="name" required maxlength="50" placeholder="enter your name" class="box">
         <input type="email" name="email" required maxlength="50" placeholder="enter your email" class="box">
         <input type="number" name="number" required maxlength="10" min="0" max="9999999999" placeholder="enter your number" class="box">
         <textarea name="message" class="box" required maxlength="1000" placeholder="enter your message" cols="30" rows="10"></textarea>
         <input id="lol" type="submit" value="send message" name="send" class="btn">
      </form>

      <div class="faq">
         <h3 class="title">FAQs</h3>
         <div class="box active">
            <h3>What are the check-in and check-out times at the hotel?</h3>
            <p>Our standard check-in time is at 12:00 PM, and check-out time is at 11:00 AM. However, we offer flexibility when possible, and early check-in or late check-out can be arranged upon request, subject to availability.</p>
         </div>
         <div class="box">
            <h3>Is parking available at the hotel, and is there a fee?</h3>
            <p>Yes, we provide onsite parking for our guests. The parking fee is Rs.100 per night for registered guests. Please let our front desk staff know if you need parking during your stay.</p>
         </div>
         <div class="box">
            <h3>Do you offer Wi-Fi access in the rooms? Is it complimentary?</h3>
            <p>Yes, we provide complimentary Wi-Fi access in all guest rooms and common areas of the hotel. You'll receive the login details at check-in to enjoy high-speed internet during your stay.</p>
         </div>
         <div class="box">
            <h3>Are pets allowed at your hotel?</h3>
            <p>We are a pet-friendly hotel. Well-behaved pets are welcome for a fee of Rs.500 per night. Please inform us in advance if you plan to bring your furry friend, so we can make necessary arrangements.</p>
         </div>
         <div class="box">
            <h3>What safety measures do you have in place regarding COVID-19?</h3>
            <p>The health and safety of our guests and staff are our top priorities. We have implemented enhanced cleaning protocols, hand sanitizing stations, and social distancing measures in all public areas. Our staff undergo regular health screenings, and face masks are required in indoor spaces as per local guidelines. Please feel free to reach out if you have specific concerns or questions about our COVID-19 safety measures.</p>
         </div>
      </div>

   </div>

</section>

<!-- contact section ends -->

<!-- reviews section starts  -->

<section class="reviews" id="reviews">

   <div class="swiper reviews-slider">

      <div class="swiper-wrapper">
         <div class="swiper-slide box">
            <img src="images/mritt.jpg" alt="">
            <h3>Mrittika Basu</h3>
            <p>"Our stay at Royal-Blu Haven was absolutely royal! The luxurious rooms, impeccable service, and stunning views made our anniversary unforgettable. We can't wait to return for another special occasion."</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/debjit.jpg" alt="">
            <h3>Debjit Basu</h3>
            <p>"A business trip turned into a leisurely stay at Royal-Blu Haven, and it was a delightful surprise. The elegant conference facilities and the spa were great for work and relaxation. The attentive staff made my stay productive and enjoyable."</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/shiba.jpg" alt="">
            <h3>Shibadrita Ghosh</h3>
            <p>"We had a family reunion at Royal-Blu Haven, and it was a blast! The kids loved the pool, and the adults enjoyed the nearby attractions. The hotel's spacious suites and accommodating staff made our gathering a memorable one."</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/aritra.jpeg" alt="">
            <h3>Aritra Majumdar</h3>
            <p>"Royal-Blu Haven provided a dreamy backdrop for our wedding. The elegant event spaces, attentive wedding planning team, and the picturesque garden for our ceremony made our big day truly magical. We couldn't have asked for more."</p>
         </div>
         
         <div class="swiper-slide box">
            <img src="images/abik.jpeg" alt="">
            <h3>Abik Saha</h3>
            <p>"The dining experience at Royal-Blu Haven is top-notch. From the breakfast buffet to the gourmet dinners, the culinary delights were outstanding. The restaurant's ambiance and service added to the overall satisfaction."</p>
         </div>
         <div class="swiper-slide box">
            <img src="images/arbit.jpg" alt="">
            <h3>Arbit Saha</h3>
            <p>"I booked a room with a breathtaking ocean view at Royal-Blu Haven, and it exceeded my expectations. The private balcony was the perfect spot to unwind. The sound of the waves and the comfortable bed ensured a great night's sleep."</p>
         </div>
      </div>

      <div class="swiper-pagination"></div>
   </div>

</section>

<!-- reviews section ends  -->





<?php include 'components/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/swiper@8/swiper-bundle.min.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js"></script>

<!-- custom js file link  -->
<script src="js/script.js"></script>

<?php include 'components/message.php'; ?>

</body>
</html>