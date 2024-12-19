// Get DOM elements
const productGrid = document.getElementById("product-grid");
const modal = document.getElementById("product-modal");
const closeModalBtn = document.querySelector(".close-btn");
const addToCartBtn = document.getElementById("add-to-cart-btn");
const continueShoppingBtn = document.getElementById("continue-shopping-btn");

let currentPage = 1;
const productsPerPage = 12;

// Fetch products dynamically
async function fetchProducts(page = 1) {
  try {
    const response = await fetch(`database.php?page=${page}&limit=${productsPerPage}`);
    if (!response.ok) throw new Error("Failed to fetch products");
    
    const data = await response.json();
    displayProducts(data.products);
    updatePagination(page, data.totalPages);
  } catch (error) {
    console.error("Error fetching products:", error);
    productGrid.innerHTML = "<p>Error loading products. Please try again later.</p>";
  }
}

// Display products
function displayProducts(products) {
  productGrid.innerHTML = ""; // Clear previous products
  products.forEach(product => {
    const productCard = document.createElement("div");
    productCard.className = "product-card";
    productCard.innerHTML = `
      <img src="${product.image_url}" alt="${product.name}">
      <h2>${product.name}</h2>
      <p>Ksh.${product.price}</p>
      <p class="description">${product.description.slice(0, 50)}...</p>
      <span class="read-more" onclick="viewFullProduct(${product.id})">Read More</span>
      <button class="buy-button" onclick="addToCart(${product.id})">Add to Cart</button>
    `;
    productGrid.appendChild(productCard);
  });
}

function viewFullProduct(productId) {
    fetch(`database.php?id=${productId}`)
      .then(response => {
        if (!response.ok) throw new Error("Failed to fetch product details");
        return response.json();
      })
      .then(product => {
        console.log("Product fetched:", product); // Debugging: Check the fetched product object
  
        if (!product || Object.keys(product).length === 0) {
          alert("Product details not found.");
          return;
        }
  
        // Check and update modal fields with defensive programming
        document.getElementById("modal-product-name").textContent = product.name || "No name available";
        document.getElementById("modal-product-description").textContent = product.description || "No description available.";
        document.getElementById("modal-product-price").textContent = product.price ? `Ksh.${product.price}` : "Price not available";
        document.getElementById("modal-product-stock").textContent = product.stock ? `Stock: ${product.stock}` : "Stock information not available";
        document.getElementById("modal-product-image").src = product.image_url || "placeholder.png";
  
        // Ensure modal is displayed
        modal.style.display = "flex";
      })
      .catch(error => {
        console.error("Error fetching product details:", error);
        alert("Failed to load product details.");
      });
  }
  

// Close the modal
closeModalBtn.addEventListener("click", () => {
  modal.style.display = "none";
});

continueShoppingBtn.addEventListener("click", () => {
  modal.style.display = "none";
});

// Update pagination buttons
function updatePagination(currentPage, totalPages) {
  document.getElementById("prev-page").disabled = currentPage === 1;
  document.getElementById("next-page").disabled = currentPage === totalPages;
  document.getElementById("page-info").textContent = `Page ${currentPage} of ${totalPages}`;
}

// Pagination controls
document.getElementById("prev-page").addEventListener("click", () => {
  if (currentPage > 1) {
    currentPage--;
    fetchProducts(currentPage);
  }
});

document.getElementById("next-page").addEventListener("click", () => {
  currentPage++;
  fetchProducts(currentPage);
});

// Load products on page load
document.addEventListener("DOMContentLoaded", () => fetchProducts(currentPage));

async function addToCart(productId) {  
    try {  
        console.log("Adding product to cart:", productId); // Debugging  

        const response = await fetch('update_cart.php', {  
            method: 'POST',  
            headers: {  
                'Content-Type': 'application/json',  
            },  
            body: JSON.stringify({ productId: productId, quantity: 1 }), // Adjust quantity as needed  
        });  

        const result = await response.json();  
        console.log("Server response:", result); // Debugging  

        if (response.ok) {  
            if (result.success) {  
                // Update UI to show success message  
                document.getElementById('cart-message').innerText = result.success;  
                // Optionally, update cart summary, e.g.:  
                document.getElementById('total-amount').innerText = 'Ksh. ' + result.totalPrice;  
            } else {  
                // Show error message from server  
                document.getElementById('cart-message').innerText = result.error || "Failed to add product to cart.";  
            }  
        } else {  
            document.getElementById('cart-message').innerText = "Failed to add product to cart. Please try again.";  
        }  
    } catch (error) {  
        console.error("Error adding product to cart:", error);  
        document.getElementById('cart-message').innerText = "An error occurred while adding the product to your cart.";  
    }  
} 

function removeFromCart(productId) {  
    fetch('cart-summary.php', {  
        method: 'POST',  
        headers: { 'Content-Type': 'application/json' },  
        body: JSON.stringify({ action: 'remove', productId: productId })  
    })  
    .then(response => response.json())  
    .then(data => {  
        if (data.success) {  
            // Remove the item from the UI  
            const itemElement = document.getElementById(`item-${productId}`);  
            if (itemElement) {  
                itemElement.remove();  
            }  

            // Update totals dynamically  
            document.getElementById('total-amount').innerText = 'Ksh. ' + data.total;  
            document.getElementById('discount-amount').innerText = 'Ksh. ' + data.discount;  
            document.getElementById('pay-now').innerText = 'Ksh. ' + data.pay_now;  

            // Check if cart is empty and update UI accordingly  
            if (data.emptyCart) {  
                document.getElementById('cart-items').innerHTML = "<li id='empty-cart'>Your cart is empty</li>";  
            }  
        } else {  
            // Display error message without an alert  
            document.getElementById('cart-message').innerText = data.error || 'Failed to remove item';  
        }  
    })  
    .catch(error => {  
        console.error('Error:', error);  
        document.getElementById('cart-message').innerText = "Error removing item from cart.";  
    });  
}

function applyDiscount() {  
    const discountCode = document.getElementById('discountCode').value;  

    fetch('cart-summary.php', {  
        method: 'POST',  
        headers: { 'Content-Type': 'application/json' },  
        body: JSON.stringify({ discountCode: discountCode })  
    })  
    .then(response => response.json())  
    .then(data => {  
        if (data.success) {  
            // Update cart totals  
            document.getElementById('total-amount').innerText = 'Ksh. ' + data.total; // Adjust formatting  
            document.getElementById('discount-amount').innerText = 'Ksh. ' + data.discount; // Adjust formatting  
            document.getElementById('pay-now').innerText = 'Ksh. ' + data.pay_now; // Adjust formatting  
        } else {  
            // Notify about the error without an alert  
            document.getElementById('cart-message').innerText = data.error || 'Failed to apply discount';  
        }  
    })  
    .catch(error => {  
        console.error('Error applying discount:', error);  
        document.getElementById('cart-message').innerText = "Error applying discount.";  
    });  
}