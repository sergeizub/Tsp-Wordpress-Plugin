<?php
/*
Plugin Name: Travel Sports Pro
Description: Plugin for Travel Sports Pro.
Version: 1.0
Requires at least: 5
Requires PHP: 5.6
Author: TSP
Author URI: https://clients.travelsportspro.com/
License: GPL v2 or later
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}
define('TSP_PHPDATE', 'M j, Y');
require_once( trailingslashit( dirname( __FILE__ ) ) . 'autoloader.php' );

function tsp_location_sort($a, $b) {
	return strcmp($a->LOCATION,$b->LOCATION);
}
function tsp_class_schedules_sort($a, $b) {
	return strtotime($a->data[0]->START_DATE) - strtotime($b->data[0]->START_DATE);
}

function tsp_array_map($func, $arr)
{
  $ret = array();
  foreach ($arr as $key => $val)
	$ret[$key] = (is_array($val) ? tsp_array_map($func, $val) : $func($val));

  return $ret;
}

function tsp_body_class( $classes ) {
    global $post;
    if( isset($post->post_content) && has_shortcode( $post->post_content, 'tsp_client' ) ) {
        $classes [] = 'tsp_body';
    }
    return $classes ;
}
add_filter( 'body_class', 'tsp_body_class' );

$tsp_app = new TravelSportsPro\App();
