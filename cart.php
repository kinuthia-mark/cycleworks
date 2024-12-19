<!DOCTYPE html>  
<html lang="en">  
<head>  
  <meta charset="UTF-8">  
  <meta name="viewport" content="width=device-width, initial-scale=1.0">  
  <title>Checkout - CycleWorks Enterprises</title>  
  <link rel="stylesheet" href="global.css">  
  <link rel="stylesheet" href="cart.css">  
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">  
</head>  
<body>  
  <header>  
    <nav class="navbar">  
      <div class="logo"><a>CYCLEWORKS ENTERPRISES</a></div>  
      <div class="menu">  
        <a href="index.php">HOME</a>  
        <a href="category.php">SHOP</a>  
        <a href="about.php">ABOUT US</a>  
        <a href="contact.php">CONTACT US</a>  
      </div>  
    </nav>  
  </header>  

  <main>  
    <div class="checkout-container">  
      <form id="checkout-form" action="cart-summary.php" method="POST">  
        <h1 class="form-header">Checkout</h1>  

        <div class="order-summary">  
          <h2>Order Summary</h2>  
          <ul id="cart-items">  
            <?php   
            if (isset($_SESSION['cart']['items']) && count($_SESSION['cart']['items']) > 0) {  
                foreach ($_SESSION['cart']['items'] as $productId => $item) {  
                    echo "<li id='item-$productId'>  
                            {$item['name']} - Ksh. " . number_format($item['price'], 2) . " x {$item['quantity']}   
                            <button type='button' onclick='removeFromCart($productId)'>Remove</button>  
                          </li>";  
                }  
            } else {  
                echo "<li id='empty-cart'>Your cart is empty</li>";  
            }  
            ?>  
          </ul>  
          <p><strong>Total:</strong> Ksh. <span id="total-amount"><?php echo number_format($_SESSION['cart']['total'] ?? 0, 2); ?></span></p>  
          <p><strong>Discount:</strong> Ksh. <span id="discount-amount"><?php echo number_format($_SESSION['cart']['discount'] ?? 0, 2); ?></span></p>  
          <p><strong>To be Paid:</strong> Ksh. <span id="pay-now"><?php echo number_format($_SESSION['cart']['pay_now'] ?? 0, 2); ?></span></p>  
        </div>  

        <!-- Discount Code -->  
        <div class="form-section">  
          <label for="discountCode">Enter Discount Code:</label>  
          <input type="text" name="discountCode" id="discountCode" aria-label="Discount code">  
          <button type="button" onclick="applyDiscount()">Apply Discount</button>  
        </div>  

        <!-- Contact Details -->  
        <div class="form-section">  
          <h2>Contact Details</h2>  
          <label for="name">Full Name*</label>  
          <input type="text" id="name" name="name" required>  

          <label for="email">Email*</label>  
          <input type="email" id="email" name="email" required>  

          <label for="phone">Phone Number*</label>  
          <input type="tel" id="phone" name="phone" placeholder="+254..." required>  
        </div>  

        <!-- M-Pesa Payment -->  
        <div class="form-section">  
          <h2>Payment Method</h2>  
          <label for="mpesa-number">M-Pesa Number*</label>  
          <input type="tel" id="mpesa-number" name="mpesa_number" placeholder="+254..." required>  
        </div>  

        <!-- Submit Button -->  
        <button type="submit" class="place-order">Place Order</button>  

        <!-- Message Display Area -->  
        <div id="cart-message"></div>  
      </form>  
    </div>  
  </main>  

  <footer>  
    <div class="footer-content">  
      <p>&copy; 2024 CycleWorks Enterprises. All Rights Reserved.</p>  
      <div class="footer-icons">  
        <a href="https://www.facebook.com/profile.php?id=61555845413032"><i class="fab fa-facebook-f"></i></a>  
        <a href="https://twitter.com"><i class="fab fa-twitter"></i></a>  
        <a href="https://www.instagram.com/cycleworkske/"><i class="fab fa-instagram"></i></a>  
      </div>  
    </div>  
  </footer>  

</body>  
</html>