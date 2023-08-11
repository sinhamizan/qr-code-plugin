<?php

/**
 * Plugin Name:       QR Code
 * Plugin URI:        https://github.com/sinhamizan/qr-code-plugin
 * Description:       Add QR Code in your posts or pages.
 * Version:           1.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            Mizanur Rahaman
 * Author URI:        https://github.com/sinhamizan
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Update URI:        https://github.com/sinhamizan/qr-code-plugin
 * Text Domain:       qr-code
 * Domain Path:       /languages
 */


function testplugin_load_textdomain() {
  load_plugin_textdomain( 'qr-code', false, dirname(__FILE__).'/languages' );
}
add_action('plugins_loaded', 'testplugin_load_textdomain');



function qrc_show_qr_code( $content ){
  $qr_post_id = get_the_ID();
  $qr_post_url = urlencode(get_the_permalink( $qr_post_id ));

  $width  = get_option( 'qrc_width' );
  $height = get_option( 'qrc_height' );
  $width  = $width ? $width : 150;
  $height = $height ? $height : 150;

  $qr_code_size = "{$width}x{$height}";

  $qr_code_img = sprintf( '<img src="https://api.qrserver.com/v1/create-qr-code/?size=%s&ecc=L&qzone=1&data=%s" alt="QR Code">', $qr_code_size, $qr_post_url );
  $content .= $qr_code_img;

  return $content;
}
add_filter( 'the_content', 'qrc_show_qr_code' );


// 
function qrc_qr_code_settings() {
  add_settings_section( 'qrc_dimention', __( 'QR Code', 'qr-code' ), 'qrc_dimention_display', 'general' );

  add_settings_field( 'qrc_width', __( 'QR Code Width', 'qr-code' ), 'qrc_display_heightWidth', 'general', 'qrc_dimention', array('qrc_width') );
  add_settings_field( 'qrc_height', __( 'QR Code Height', 'qr-code' ), 'qrc_display_heightWidth', 'general', 'qrc_dimention', array('qrc_height') );

  register_setting( 'general', 'qrc_width', array( 'sanitize_callback' => 'esc_attr' ) );
  register_setting( 'general', 'qrc_height', array( 'sanitize_callback' => 'esc_attr' ) );
}
add_action( 'admin_init', 'qrc_qr_code_settings' );


function qrc_dimention_display(){
  echo "<p>".__( 'QR Code Image Dimention', 'qr-code' )."</p>";
}


function qrc_display_heightWidth($args) {
  $heightWidth = get_option( $args[0] );
  printf('<input type="text" id="%s" name="%s" value="%s">', $args[0], $args[0], $heightWidth );
}

