<!DOCTYPE html>
<html lang="en">
<head>
  <!-- Meta Information -->
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Contact Us - CycleWorks Enterprises</title>

  <!-- Stylesheets -->
  <link rel="stylesheet" href="global.css">
  <link rel="stylesheet" href="contact.css">

  <!-- Font Awesome for Icons -->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
</head>

<body>
  <!-- Full Page Overlay -->
  <div class="overlay">

    <!-- Header Section -->
    <header>
      <nav class="navbar">
        <!-- Logo -->
        <div class="logo">
          <a>CYCLEWORKS ENTERPRISES</a>
        </div>

        <!-- Navigation Links -->
        <div class="menu">
          <a href="index.php">HOME</a>
          <a href="category.php">SHOP</a>
          <a href="about.php">ABOUT US</a>
          <a href="contact.php" class="active">CONTACT US</a>
        </div>
      </nav>
    </header>

    <!-- Main Content -->
    <main>
      <!-- Hero Section -->
      <section class="contact-hero">
        <h1>Contact Us</h1>
        <p>We’re here to help. Reach out to us for inquiries, support, or feedback.</p>
      </section>

      <!-- Contact Information Section -->
      <section class="contact-info">
        <div class="info-container">
          <!-- Contact Details -->
          <div class="contact-details">
            <h2>Contact Details</h2>
            <ul>
              <li><strong>Phone:</strong> <a href="tel:+2547895323">+254 789 5323</a></li>
              <li><strong>Email:</strong> <a href="mailto:support@cycleworks.com">support@cycleworks.com</a></li>
            </ul>
          </div>

          <!-- Social Media Links -->
          <div class="social-media">
            <h2>Follow Us</h2>
            <div class="social-icons">
              <a href="https://facebook.com" aria-label="Facebook"><i class="fab fa-facebook-f"></i></a>
              <a href="https://twitter.com" aria-label="Twitter"><i class="fab fa-twitter"></i></a>
              <a href="https://instagram.com" aria-label="Instagram"><i class="fab fa-instagram"></i></a>
            </div>
          </div>
        </div>
      </section>

      <!-- Contact Form Section -->
      <section class="contact-form">
        <h2>Send Us a Message</h2>
        <p>Fill out the form below, and we’ll respond as soon as possible.</p>
        <div class="form-card">
          <form action="contact.php" method="POST">
            <div class="form-group">
              <label for="name">Your Name*</label>
              <input type="text" id="name" name="name" placeholder="John Doe" required>
            </div>

            <div class="form-group">
              <label for="email">Your Email*</label>
              <input type="email" id="email" name="email" placeholder="you@example.com" required>
            </div>

            <div class="form-group">
              <label for="message">Your Message*</label>
              <textarea id="message" name="message" rows="5" placeholder="Type your message here..." required></textarea>
            </div>

            <button type="submit" class="submit-button">Send Message</button>
          </form>
        </div>
      </section>
    </main>

    <!-- Footer -->
    <footer>
      <div class="footer-content">
        <p>&copy; 2024 CycleWorks Enterprises. All Rights Reserved.</p>
      </div>
    </footer>
  </div>

  <?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $message = htmlspecialchars(trim($_POST['message']));

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid email format. Please go back and try again.";
        exit;
    }

    $to = "client@example.com";
    $subject = "New Contact Form Submission from $name";

    $emailContent = "
    You have received a new message:\n\n
    Name: $name\n
    Email: $email\n\n
    Message:\n$message\n
    ";

    $headers = "From: $email\r\nReply-To: $email\r\n";

    if (mail($to, $subject, $emailContent, $headers)) {
        header("Location: thank-you.html");
        exit;
    } else {
        echo "Something went wrong. Please try again.";
    }
}
?>

</body>
</html>
