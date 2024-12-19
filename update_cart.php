<?php  
session_start();  

header('Content-Type: application/json');  

// Database connection  
$host = 'localhost';  
$user = 'root'; // Add your DB username  
$password = ''; // Add your DB password  
$database = 'cycleworks enterprises'; // Ensure this matches your actual DB name  

$conn = new mysqli($host, $user, $password, $database);  
if ($conn->connect_error) {  
    echo json_encode(['error' => 'Database connection failed.', 'details' => $conn->connect_error]);  
    exit;  
}  

// Read and decode JSON input  
$rawInput = file_get_contents('php://input');  
$data = json_decode($rawInput, true);  

if (!$data) {  
    echo json_encode(['error' => 'No valid JSON input received.', 'rawInput' => $rawInput]);  
    exit;  
}  

// Validate productId and quantity  
if (!isset($data['productId'], $data['quantity']) || !is_numeric($data['productId']) || !is_numeric($data['quantity'])) {  
    echo json_encode(['error' => 'Invalid input data.', 'rawInput' => $rawInput]);  
    exit;  
}  

$productId = intval($data['productId']);  
$quantity = intval($data['quantity']);  

// Fetch product details  
$sql = "SELECT name, price, stock FROM products WHERE id = ?";  
$stmt = $conn->prepare($sql);  

if ($stmt) {  
    $stmt->bind_param("i", $productId);  
    $stmt->execute();  
    $result = $stmt->get_result();  
    $product = $result->fetch_assoc();  
    $stmt->close();  

    if ($product) {  
        $newStock = $product['stock'] - $quantity;  
        if ($newStock >= 0) {  
            // Update stock in the database  
            $updateSql = "UPDATE products SET stock = ? WHERE id = ?";  
            $updateStmt = $conn->prepare($updateSql);  
            if ($updateStmt) {  
                $updateStmt->bind_param("ii", $newStock, $productId);  
                $updateStmt->execute();  
                $updateStmt->close();  
            } else {  
                echo json_encode(['error' => 'Failed to prepare stock update statement.', 'sqlError' => $conn->error]);  
                exit;  
            }  

            // Ensure cart session is initialized  
            if (!isset($_SESSION['cart'])) {  
                $_SESSION['cart'] = [];  
            }  

            // Add to cart  
            if (isset($_SESSION['cart'][$productId])) {  
                $_SESSION['cart'][$productId]['quantity'] += $quantity;  
            } else {  
                $_SESSION['cart'][$productId] = [  
                    'name' => $product['name'],  
                    'price' => $product['price'],  
                    'quantity' => $quantity,  
                ];  
            }  

            // Calculate total price  
            $totalPrice = 0;  
            foreach ($_SESSION['cart'] as $item) {  
                $totalPrice += $item['price'] * $item['quantity'];  
            }  

            // Formatting price as currency  
            $totalPrice = number_format((float)$totalPrice, 2, '.', '');  

            echo json_encode([  
                'success' => 'Product added to cart.',  
                'cart' => $_SESSION['cart'],  
                'totalPrice' => $totalPrice  
            ]);  
        } else {  
            echo json_encode(['error' => 'Insufficient stock for the requested quantity.']);  
        }  
    } else {  
        echo json_encode(['error' => 'Product not found.']);  
    }  
} else {  
    echo json_encode(['error' => 'Failed to prepare SQL statement.', 'sqlError' => $conn->error]);  
}  

$conn->close();  
?>