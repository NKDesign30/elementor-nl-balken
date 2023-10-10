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
  // Get the current cart total with tax
  $cart_total = WC()->cart->get_cart_contents_total();

  // Format the cart total as a number with two decimal places
  $cart_total = number_format($cart_total, 2, '.', '');

  // Create an array with the cart total value
  $response = array(
    'cart_total' => $cart_total
  );

  // Send the JSON response
  header('Content-Type: application/json');
  echo json_encode($response);
} catch (Exception $e) {
  die(json_encode(['error' => $e->getMessage()]));
}
