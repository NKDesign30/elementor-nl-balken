<?php
ini_set('display_errors', 1);

// Load WordPress
define('WP_USE_THEMES', false);
require_once('../../../wp-load.php');

// Check if WooCommerce is active
if (!class_exists('WooCommerce')) {
  die(json_encode(['error' => 'WooCommerce not found']));
}

try {
  // Initialize session if not already initialized
  if (!WC()->session->has_session()) {
    WC()->session->set_customer_session_cookie(true);
  }

  // Debug: Log the cart content
  //error_log("Cart Content: " . print_r(WC()->cart->get_cart(), true));

  // Calculate the cart total with tax
  $cart_total_with_tax = WC()->cart->get_cart_contents_total() + WC()->cart->get_cart_contents_tax();

  // Debug: Log the cart total with tax
  // error_log("Cart Total with Tax: " . $cart_total_with_tax);

  // Format the cart total with tax as a number with two decimal places
  $cart_total_with_tax = number_format($cart_total_with_tax, 2, '.', '');

  // Create an array with the cart total with tax value
  $response = array(
    'cart_total_with_tax' => $cart_total_with_tax
  );

  // Send the JSON response
  header('Content-Type: application/json');
  echo json_encode($response);
} catch (Exception $e) {
  die(json_encode(['error' => $e->getMessage()]));
}
