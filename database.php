<?php
// Database connection settings
$host = 'localhost';
$user = 'root'; // Default XAMPP MySQL username
$password = ''; // Default XAMPP MySQL password is empty
$database = 'cycleworks enterprises';

// Create a connection
$conn = new mysqli($host, $user, $password, $database);

// Check for connection errors
if ($conn->connect_error) {
    die(json_encode(['error' => 'Database connection failed: ' . $conn->connect_error]));
}

// If `id` is provided, fetch a single product
if (isset($_GET['id'])) {
    $productId = (int) $_GET['id'];

    $sql = "SELECT name, description, price, image_url, stock FROM products WHERE id = ?";
    $stmt = $conn->prepare($sql);
    if (!$stmt) {
        die(json_encode(['error' => 'Prepared statement failed: ' . $conn->error]));
    }

    $stmt->bind_param("i", $productId);
    $stmt->execute();

    $result = $stmt->get_result();
    $product = $result->fetch_assoc();

    header('Content-Type: application/json');
    echo json_encode($product ? $product : ['error' => 'Product not found']);

    $stmt->close();
    $conn->close();
    exit;
}

// Get page and limit for pagination
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
$limit = isset($_GET['limit']) ? (int) $_GET['limit'] : 12;
$offset = ($page - 1) * $limit;

// Optional: Search query
$searchQuery = isset($_GET['search']) ? trim($_GET['search']) : '';

// Fetch products with search and pagination
$sql = "SELECT SQL_CALC_FOUND_ROWS id, name, description, price, image_url, stock 
        FROM products 
        WHERE stock > 0 AND name LIKE ? 
        LIMIT ? OFFSET ?";

$stmt = $conn->prepare($sql);
if (!$stmt) {
    die(json_encode(['error' => 'Prepared statement failed: ' . $conn->error]));
}

$searchParam = "%" . $searchQuery . "%";
$stmt->bind_param("sii", $searchParam, $limit, $offset);
$stmt->execute();

$result = $stmt->get_result();
$products = [];

while ($row = $result->fetch_assoc()) {
    $products[] = $row;
}

// Pagination metadata
$totalRowsResult = $conn->query("SELECT FOUND_ROWS() AS total");
$totalRows = $totalRowsResult->fetch_assoc()['total'] ?? 0;
$totalPages = ceil($totalRows / $limit);

header('Content-Type: application/json');
echo json_encode([
    'products' => $products,
    'totalPages' => $totalPages,
    'currentPage' => $page,
    'totalRows' => $totalRows
]);
$stmt->close();
$conn->close();
?>
