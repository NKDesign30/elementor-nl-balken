<?php

/*
Plugin Name: Elementor NL-Balken
Description: Elementor Add on
Version: 1.0.0.
Author: Niko
*/

if (!defined('ABSPATH')) exit; // Exit if accessed directly

function register_nl_balken_widget()
{
  // Check if Elementor installed and activated
  if (!did_action('elementor/loaded')) {
    add_action('admin_notices', function () {
      echo '<div class="notice notice-warning is-dismissible"><p>Elementor is not installed or activated but is required for the NL-Balken plugin.</p></div>';
    });
    return;
  }

  // Include the widget class file
  require_once(__DIR__ . '/widget-class.php');

  // Register the widget
  \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new NL_Balken());
}

add_action('elementor/widgets/widgets_registered', 'register_nl_balken_widget');

function nl_balken_styles()
{
  echo '<style>
  .progress-bar {
    position: relative;
    width: 300px;
    height: 10px;
    background-color: #F2F2F2;
    border-radius: 4px;
  }

  .progress-bar-fill {
    height: 100%;
    background-color: #90c091;
    transition: width 0.5s;
  }

  .progress-bar-circle {
    position: absolute;
    top: -9px;
    width: 28px;
    height: 28px;
    border-radius: 50%;
    background-color: #F2F2F2;
    filter: drop-shadow(0px 0px 5px rgba(0, 0, 0, 0.25));
    flex-shrink: 0;
    right: 0%;
  }

  .goal-reached {
    background-color: #90c091 !important;
  }

  /* Add the style for the speech bubble */
  .progress-bar-circle::before {
    content: attr(data-discount);
    font-weight: 900;
    font-size: 24px;
    position: absolute;
    top: -55px;
    left: 50%;
    margin-left: -34px;
    width: 68px;
    height: 44px;
    line-height: 40px;
    text-align: center;
    color: #c8c8c8;
    background-color: #F2F2F2;
    border-radius: 4px;
    z-index: 1;
    font-family: Roboto,sans-serif;
  }

  .progress-bar-circle:nth-of-type(2)::before {
    font-family: "Font Awesome 5 Free";
    content: "\f48b";
    font-weight: 900;
    font-size: 24px;
    position: absolute;
    top: -55px;
    left: 50%;
    margin-left: -34px;
    width: 68px;
    height: 44px;
    line-height: 40px;
    text-align: center;
    color: #c8c8c8;
    background-color: #F2F2F2;
    border-radius: 4px;
    z-index: 1;
  }
  .goal-reached {
    background-color: #90c091 !important;
  }
  
  .progress-bar-circle:nth-of-type(3)::before {
    content: attr(data-discount);
    font-weight: 900;
    font-size: 24px;
    position: absolute;
    top: -55px;
    left: 50%;
    margin-left: -34px;
    width: 68px;
    height: 44px;
    line-height: 40px;
    text-align: center;
    color: #c8c8c8;
    background-color: #F2F2F2;
    border-radius: 4px;
    z-index: 1;
    font-family: Roboto,sans-serif;
  }

  /* Add the triangle for the speech bubble */
  .progress-bar-circle::after {
    content: "";
    position: absolute;
    top: -11px;
    left: 50%;
    margin-left: -8px;
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-bottom: 8px solid #F2F2F2;
    z-index: 2;
    rotate: 200grad;
  }

  .progress-bar > .progress-bar-circle:nth-of-type(2) { right: 60%; }
  .progress-bar > .progress-bar-circle:nth-of-type(3) { right: 30%; }

  /* Ändere die Farbe des Textes in der Sprechblase und des Dreiecks auf Weiß */
  .progress-bar-circle.goal-reached,
  .progress-bar-circle.goal-reached::before,
  .progress-bar-circle.goal-reached::after {
    background-color: #90c091 !important;
  }
  
  .progress-bar-circle.goal-reached::before {
    color: #ffffff; /* Ändere die Farbe des Textes in der Sprechblase auf Weiß */
  }
  
  /* Ändere die Farbe des Dreiecks auf #90c091 */
  .progress-bar-circle.goal-reached::after {
    border-left: 8px solid transparent;
    border-right: 8px solid transparent;
    border-bottom: 8px solid #90c091;
    background-color: transparent!important;
  }
  .progress-bar-text {
    margin-top: 15px;
    width: 30%;
    font-size: 12px;
  }
  
  .progress-bar-text .highlight {
    color: #90c091;
    font-weight: bold;
  }
  
  .progress-bar-text .learn-more {
    color: #CFC47E;
    text-decoration: none;
  }
  
  .progress-bar-text .learn-more:hover {
    text-decoration: underline;
  }
  
</style>';
}

add_action('wp_head', 'nl_balken_styles');
