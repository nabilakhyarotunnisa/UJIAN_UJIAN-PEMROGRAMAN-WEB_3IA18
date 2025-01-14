<?php
@include 'config.php';

if (isset($_POST['add_to_cart'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];
    $product_image = $_POST['product_image'];
    $product_quantity = 1;  // Quantity default

    // Periksa apakah produk sudah ada di cart
    $stmt = $conn->prepare("SELECT * FROM `cart` WHERE name = ?");
    $stmt->bind_param("s", $product_name);  // Bind string parameter untuk 'name'
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $message[] = 'Product already added to cart';
    } else {
        // Tambahkan produk ke cart
        $stmt = $conn->prepare("INSERT INTO `cart` (name, price, image, quantity) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("sdsi", $product_name, $product_price, $product_image, $product_quantity);  // Bind params
        if ($stmt->execute()) {
            $message[] = 'Product added to cart successfully';
        } else {
            $message[] = 'Failed to add product to cart: ' . $stmt->error;
        }
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>products</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">
</head>
<body>

<?php
if (isset($message)) {
   foreach ($message as $msg) {
      echo '<div class="message"><span>' . $msg . '</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i></div>';
   }
}
?>

<?php include 'header.php'; ?>

<div class="container">
   <section class="products">
      <h1 class="heading">latest products</h1>
      <div class="box-container">
         <?php
         $select_products = mysqli_query($conn, "SELECT * FROM `products`");
         if (mysqli_num_rows($select_products) > 0) {
            while ($fetch_product = mysqli_fetch_assoc($select_products)) {
         ?>
         <form action="" method="post">
            <div class="box">
               <img src="uploaded_img/<?php echo $fetch_product['image']; ?>" alt="">
               <h3><?php echo $fetch_product['name']; ?></h3>
               <div class="price">Rp. <?php echo number_format($fetch_product['price']); ?>/-</div>
               <input type="hidden" name="product_name" value="<?php echo $fetch_product['name']; ?>">
               <input type="hidden" name="product_price" value="<?php echo $fetch_product['price']; ?>">
               <input type="hidden" name="product_image" value="<?php echo $fetch_product['image']; ?>">
               <input type="submit" class="btn" value="Add to Cart" name="add_to_cart">
            </div>
         </form>
         <?php
            }
         } else {
            echo "<p class='empty'>No products available!</p>";
         }
         ?>
      </div>
   </section>
</div>

<script src="js/script.js"></script>
</body>
</html>
