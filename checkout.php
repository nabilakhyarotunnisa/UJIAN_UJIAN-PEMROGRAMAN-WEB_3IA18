<?php
@include 'config.php';

if (isset($_POST['order_btn'])) {
    $name = $_POST['name'];
    $number = $_POST['number'];
    $email = $_POST['email'];
    $method = $_POST['method'];
    $flat = $_POST['flat'];
    $street = $_POST['street'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $country = $_POST['country'];
    $pin_code = $_POST['pin_code'];

    $cart_query = mysqli_query($conn, "SELECT name, price, quantity FROM `cart`");
    $price_total = 0;
    $product_name = []; // Inisialisasi array produk

    if (mysqli_num_rows($cart_query) > 0) {
        while ($product_item = mysqli_fetch_assoc($cart_query)) {
            // Pastikan 'quantity' ada dalam array
            if (isset($product_item['quantity'])) {
                $product_name[] = $product_item['name'] . ' (' . $product_item['quantity'] . ')';
                $product_price = $product_item['price'] * $product_item['quantity'];
                $price_total += $product_price;
            }
        }
    }

    $total_product = implode(', ', $product_name);

    // Query untuk memasukkan data ke dalam tabel `order`
    $detail_query = mysqli_query($conn, "INSERT INTO `order`(name, number, email, method, flat, street, city, state, country, pin_code, total_products, total_price) 
    VALUES('$name', '$number', '$email', '$method', '$flat', '$street', '$city', '$state', '$country', '$pin_code', '$total_product', '$price_total')")
    or die('Query failed: ' . mysqli_error($conn));


    if ($cart_query && $detail_query) {
        echo "
        <div class='order-message-container'>
            <div class='message-container'>
                <h3>Thank you for shopping!</h3>
                <div class='order-detail'>
                    <span>" . $total_product . "</span>
                    <span class='total'> Total: Rp. " . number_format($price_total) . "/- </span>
                </div>
                <div class='customer-details'>
                    <p>Your name: <span>" . $name . "</span></p>
                    <p>Your number: <span>" . $number . "</span></p>
                    <p>Your email: <span>" . $email . "</span></p>
                    <p>Your address: <span>" . $flat . ", " . $street . ", " . $city . ", " . $state . ", " . $country . " - " . $pin_code . "</span></p>
                    <p>Your payment mode: <span>" . $method . "</span></p>
                    <p>(*Pay when product arrives*)</p>
                </div>
                <a href='products.php' class='btn'>Continue shopping</a>
            </div>
        </div>
        ";
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

   <!-- font awesome cdn link -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link -->
   <link rel="stylesheet" href="css/style.css">
</head>

<body>

   <?php include 'header.php'; ?>

   <div class="container">

      <section class="checkout-form">

         <h1 class="heading">Complete your order</h1>

         <form action="" method="post">

            <div class="display-order">
               <?php
               $select_cart = mysqli_query($conn, "SELECT * FROM `cart`");
               $grand_total = 0;

               if (mysqli_num_rows($select_cart) > 0) {
                  while ($fetch_cart = mysqli_fetch_assoc($select_cart)) {
                     $total_price = $fetch_cart['price'] * $fetch_cart['quantity']; // Operasi matematika
                     $grand_total += $total_price; // Akumulasi total harga
                     ?>
                     <span><?= $fetch_cart['name']; ?> (<?= $fetch_cart['quantity']; ?>)</span>
                     <?php
                  }
               } else {
                  echo "<div class='display-order'><span>Your cart is empty!</span></div>";
               }
               ?>
               <span class="grand-total">Grand total: Rp. <?= number_format($grand_total); ?>/-</span>
            </div>

            <div class="flex">
               <div class="inputBox">
                  <span>Your name</span>
                  <input type="text" placeholder="Enter your name" name="name" required>
               </div>
               <div class="inputBox">
                  <span>Your number</span>
                  <input type="number" placeholder="Enter your number" name="number" required>
               </div>
               <div class="inputBox">
                  <span>Your email</span>
                  <input type="email" placeholder="Enter your email" name="email" required>
               </div>
               <div class="inputBox">
                  <span>Payment method</span>
                  <select name="method">
                     <option value="cash on delivery" selected>Cash on delivery</option>
                     <option value="credit card">Credit card</option>
                     <option value="paypal">PayPal</option>
                  </select>
               </div>
               <div class="inputBox">
                  <span>Address line 1</span>
                  <input type="text" placeholder="e.g. Flat no." name="flat" required>
               </div>
               <div class="inputBox">
                  <span>Address line 2</span>
                  <input type="text" placeholder="e.g. Street name" name="street" required>
               </div>
               <div class="inputBox">
                  <span>City</span>
                  <input type="text" placeholder="e.g. Bogor" name="city" required>
               </div>
               <div class="inputBox">
                  <span>State</span>
                  <input type="text" placeholder="e.g. Jawa Barat" name="state" required>
               </div>
               <div class="inputBox">
                  <span>Country</span>
                  <input type="text" placeholder="e.g. Indonesia" name="country" required>
               </div>
               <div class="inputBox">
                  <span>Pin code</span>
                  <input type="text" placeholder="e.g. 123456" name="pin_code" required>
               </div>
            </div>
            <input type="submit" value="Order now" name="order_btn" class="btn">
         </form>

      </section>

   </div>

   <!-- custom js file link -->
   <script src="js/script.js"></script>

</body>

</html>