<?php

use Elementor\Widget_Base;
use Elementor\Controls_Manager;

class NL_Balken extends Widget_Base
{

  public function get_name()
  {
    return 'nl-balken';
  }

  public function get_title()
  {
    return __('NL-Balken', 'elementor');
  }

  public function get_icon()
  {
    return 'fa fa-code';
  }

  public function get_categories()
  {
    return ['general'];
  }

  protected function _register_controls()
  {
    $this->start_controls_section(
      'content_section',
      [
        'label' => __('Content', 'elementor'),
        'tab' => Controls_Manager::TAB_CONTENT,
      ]
    );

    // Add your controls here. For example, a control for the threshold values.

    $this->end_controls_section();
  }

  /*protected function render()
  {
    // Get the current cart total
    $cart_total = WC()->cart->get_cart_contents_total();

    // Get the thresholds
    $thresholds = get_option('wc_progress_bar_discount_thresholds', []);

    // Calculate the progress
    $progress = 0;
    $max_threshold = $thresholds[2]['amount']; // Get the maximum threshold value

    if ($cart_total <= $max_threshold) {
      $progress = ($cart_total / $max_threshold) * 100;
    } else {
      $progress = 100;
    }

    // Display the progress bar
    echo '<div class="progress-bar">
            <div class="progress-bar-fill" style="width: ' . $progress . '%"></div>
            <div class="progress-bar-circle" data-discount="' . $thresholds[1]['discount'] . '"></div>
            <div class="progress-bar-circle" data-discount="' . $thresholds[2]['discount'] . '"></div>
            <div class="progress-bar-circle"></div>
        </div>';
  }*/
  protected function render()
  {
    // Get the current cart total
    $cart_total = WC()->cart->get_cart_contents_total();

    // Get the thresholds
    $thresholds = get_option('wc_progress_bar_discount_thresholds', []);

    // Get the third threshold value
    $max_threshold = $thresholds[2]['amount'];

    // Calculate the progress
    $progress = ($cart_total / $max_threshold) * 100;

    // Berechne den Wert des Warenkorbs inklusive Mehrwertsteuer
    $cart_total_incl_tax = WC()->cart->get_total('incl');

    // Füge den Wert des Warenkorbs inklusive Mehrwertsteuer in das versteckte Element ein
    echo '<div id="cart-total" style="display:none;">' . esc_attr($cart_total_incl_tax) . '</div>';

    // Calculate the remaining amount to reach the next discount threshold
    $remaining_amount = 0;
    $next_discount = 0;

    if ($cart_total < $thresholds[0]['amount']) {
      $remaining_amount = $thresholds[0]['amount'] - $cart_total;
      $next_discount = $thresholds[0]['discount'];
    } elseif ($cart_total < $thresholds[1]['amount']) {
      $remaining_amount = $thresholds[1]['amount'] - $cart_total;
      $next_discount = $thresholds[1]['discount'];
    } elseif ($cart_total < $thresholds[2]['amount']) {
      $remaining_amount = $thresholds[2]['amount'] - $cart_total;
      $next_discount = $thresholds[2]['discount'];
    }

    // If the remaining amount is negative, set it to 0
    $remaining_amount = max(0, $remaining_amount);
    // Display the progress bar
    echo '<div class="progress-bar" data-thresholds="' . esc_attr(json_encode($thresholds)) . '">
      <div class="progress-bar-fill" style="width: ' . $progress . '%"></div>
      <div class="progress-bar-circle" data-discount="' . $thresholds[0]['discount'] . '"></div>
      <div class="progress-bar-circle" data-discount="' . $thresholds[1]['discount'] . '"></div>
      <div class="progress-bar-circle" data-discount="' . $thresholds[2]['discount'] . '"></div>
  </div>';

    // Display the text
    echo '<div class="progress-bar-text">';
    echo 'Füge noch <span class="highlight">' . wc_price($remaining_amount) . '</span> deinem Warenkorb hinzu, um ';
    echo '<span class="highlight">' . $next_discount . '% Rabatt</span> auf deinen Warenkorb zu erhalten. ';
    echo '<a href="https://neurolab-vital.de/lieferkonditionen/" class="learn-more">Mehr erfahren</a>';
    echo '</div>';
  }
}

add_action('elementor/widgets/widgets_registered', function ($widgets_manager) {
  $widgets_manager->register_widget_type(new NL_Balken());
});
