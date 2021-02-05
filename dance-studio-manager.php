<?php
/*
Plugin Name: Dance Studio Manager
Plugin URI: https://www.dancestudiomanager.com/
Description: Plugin for Dance Studio Manager.
Version: 1.0
Author: DSM
Author URI: https://www.dancestudiomanager.com/
*/

if ( ! defined( 'WPINC' ) ) {
	die;
}
define('DSM_PHPDATE', 'M j, Y');
require_once( trailingslashit( dirname( __FILE__ ) ) . 'autoloader.php' );

function dsm_location_sort($a, $b) {
	return strcmp($a->LOCATION,$b->LOCATION);
}
function dsm_class_schedules_sort($a, $b) {
	return strtotime($a->data[0]->START_DATE) - strtotime($b->data[0]->START_DATE);
}

function dsm_array_map($func, $arr)
{
  $ret = array();
  foreach ($arr as $key => $val)
	$ret[$key] = (is_array($val) ? dsm_array_map($func, $val) : $func($val));

  return $ret;
}

function dsm_body_class( $classes ) {
    global $post;
    if( isset($post->post_content) && has_shortcode( $post->post_content, 'dsm_client' ) ) {
        $classes [] = 'dsm_body';
    }
    return $classes ;
}
add_filter( 'body_class', 'dsm_body_class' );

$dsm_app = new DanceStudioManager\App();
