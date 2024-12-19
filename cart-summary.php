<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);

    if ($input['action'] === 'remove' && isset($input['productId'])) {
        $productId = intval($input['productId']);

        if (isset($_SESSION['cart']['items'][$productId])) {
            unset($_SESSION['cart']['items'][$productId]);

            // Recalculate totals
            $totalAmount = 0;
            foreach ($_SESSION['cart']['items'] as $item) {
                $totalAmount += $item['price'] * $item['quantity'];
            }

            $discount = $_SESSION['cart']['discount'] ?? 0;
            $payNow = $totalAmount - $discount;

            $_SESSION['cart']['total'] = $totalAmount;
            $_SESSION['cart']['pay_now'] = $payNow;

            // Respond with updated totals
            echo json_encode([
                'success' => true,
                'total' => number_format($totalAmount, 2),
                'discount' => number_format($discount, 2),
                'pay_now' => number_format($payNow, 2),
                'emptyCart' => empty($_SESSION['cart']['items']),
            ]);
        } else {
            echo json_encode(['error' => 'Item not found in cart']);
        }
        exit;
    }
}
?>