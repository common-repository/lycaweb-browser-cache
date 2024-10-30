<?php
/*
Plugin Name: Lycaweb Browser Cache
Plugin URI: http://lycaweb.com/wpthemes/blog/lycaweb-browser-cache-plugin-for-wordpress/
Description: This plugin is used to make the browser cache the headers for as long as the user indicates, unless he refreshes the page manually. It improves the experience of visitors.
Author: Lycaweb Ltd
Version: 1.0
Author URI: http://lycaweb.com
*/



//activate - deactivate browser cache

 
 
 
 function lycaweb_customize_register( $wp_customize ) {
	$wp_customize->get_setting( 'blogname' )->transport = 'postMessage';
	$wp_customize->get_setting( 'blogdescription' )->transport = 'postMessage';
}
add_action( 'customize_register', 'lycaweb_customize_register' );
 
 
 
 
 
 
 function lycaweb_theme_customizer( $wp_customize ) {
 
$wp_customize->add_section('lyca_content' , array(
	
	'title'       => __( 'Lycaweb Browser Cache', 'lycapress' ),
));

$wp_customize->add_setting(
    'lycaweb_cache_activate'
	);
	$wp_customize->add_control(
    'lycaweb_cache_activate',
    array(
        'type' => 'checkbox',
        'label' => 'Activate Cache',
        'section' => 'lyca_content',
    )
);



class lycaweb_Textarea_Control extends WP_Customize_Control {
	public $type = 'textarea';
	public function render_content() {
?>
<label>
	<span class="customize-control-title"><?php echo esc_html( $this->label ); ?></span>
	<textarea rows="1" style="width:100%;" <?php $this->link(); ?>><?php echo esc_textarea( $this->value() ); ?></textarea>
</label>

<?php } } 


$wp_customize->add_setting('thetime_cache', array('default' => '3600',));
$wp_customize->add_control(new lycaweb_Textarea_Control($wp_customize, 'thetime_cache', array(
	'label' => 'Cache time in secs',
	'section' => 'lyca_content',
	'settings' => 'thetime_cache',
)));

}
add_action('customize_register', 'lycaweb_theme_customizer');




function load_cache_code_on_header()
{
?>

<?php if( get_theme_mod( 'lycaweb_cache_activate' ) == '1') : ?>

<?php $ts = gmdate("D, d M Y H:i:s") . " GMT";

header("Expires: $ts");

header("Last-Modified: $ts");

header("Pragma: no-cache");

header("Cache-Control: no-cache, must-revalidate"); ?>

<?php $seconds_to_cache = get_theme_mod( 'thetime_cache' );

$ts = gmdate("D, d M Y H:i:s", time() + $seconds_to_cache) . " GMT";

header("Expires: $ts");

header("Pragma: cache");

header("Cache-Control: max-age=$seconds_to_cache");

 ?>

 
 <?php endif; ?>
 
<?php
}





add_action('send_headers','load_cache_code_on_header');






?>
