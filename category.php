<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Products - CycleWorks Enterprises</title>
  <link rel="stylesheet" href="global.css">
  <link rel="stylesheet" href="category.css">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>
<body>
  <!-- Header Section -->
  <header>
    <nav class="navbar">
      <div class="logo">
        <a>CYCLEWORKS ENTERPRISES</a>
      </div>
      <div class="menu">
        <a href="index.php">HOME</a>
        <a href="category.php" class="active">SHOP</a>
        <a href="about.php">ABOUT US</a>
        <a href="contact.php">CONTACT US</a>
      </div>
      <div class="right-section">
        <a href="cart.php" class="cart-icon">
          <i class="fas fa-shopping-cart"></i>
        </a>
      </div>
    </nav>
  </header>

  <!-- Main Section -->
  <main>
    <!-- Products Section -->
    <section class="category">
      <h1>Our Products</h1>
      <div id="product-grid" class="product-grid"></div>
      <div class="pagination">
        <button id="prev-page" class="pagination-button">Previous</button>
        <span id="page-info"></span>
        <button id="next-page" class="pagination-button">Next</button>
      </div>
    </section>
  </main>

  <!-- Modal for Product Details -->
  <div id="product-modal" class="product-modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <div class="modal-body">
        <img id="modal-product-image" src="placeholder.png" alt="Product Image">
        <h2 id="modal-product-name"></h2>
        <p id="modal-product-description"></p>
        <div id="modal-product-price"></div>
        <div id="modal-product-stock"></div>
        <button id="add-to-cart-btn" class="buy-button">Add to Cart</button>
        <button id="continue-shopping-btn" class="continue-shopping-btn">Continue Shopping</button>
      </div>
    </div>
  </div>

  <!-- Footer Section -->
  <footer>
    <p>&copy; 2024 CycleWorks Enterprises. All Rights Reserved.</p>
    <div class="footer-icons">
      <a href="https://www.facebook.com/profile.php?id=61555845413032"><i class="fab fa-facebook-f"></i></a>
      <a href="https://twitter.com"><i class="fab fa-twitter"></i></a>
      <a href="https://www.instagram.com/cycleworkske/"><i class="fab fa-instagram"></i></a>
    </div>
  </footer>

  <!-- JavaScript -->
  <script src="category.js"></script>
</body>
</html>
