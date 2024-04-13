<?php 

// namespace Crud;

/*
* Plugin Name: Basic Crud Plugin
* Description: This is my basic crud wordpress plugin
* Version: 1.0
* Author: Yeasin Hossain
*/

// namespace CRUD;

//if not defined the 'ABSPATH' by wordpress then exit() the execution
if( !defined('ABSPATH')) exit;

//define the necessary constants;
define('PLUGIN_DIR', plugin_dir_path(__FILE__));


//plugin activation hook
function activation(){
	require_once( PLUGIN_DIR . 'src/Activate.php');
	Activate::activate();
}

register_activation_hook(__FILE__, 'activation');

//plugin deactivation hook
function deactivation(){
	require_once PLUGIN_DIR . 'src/Deactivate.php';
	Deactivate::deactivate();
}
register_deactivation_hook(__FILE__, 'deactivation');


//create a new submenu under the 'Settings' page
add_action('admin_menu', 'create_crud_settings_page');

//create a new submenu
function create_crud_settings_page(){
	//add_options_page to create a new submenu under the settings page
	add_options_page(
		'Crud Manager',
		'Crud Manager',
		'manage_options',
		'crud_manager',
		'show_crud_manager_page'
	);
}

//create the crud_manager_page
function show_crud_manager_page(){
	?>
		<div class="wrap">

			<h1>Crud Plugin Manager Page</h1>

			<form action="options.php" method="post">
				<?php 
					settings_fields('crud_group_options');
					do_settings_sections('crud_manager');
					submit_button('Submit', 'primary');
				?>
			</form>

		</div>

	<?php 
}




function sanitize_form_data($input){
	$valid = array();
	$valid['name'] = preg_replace(
	'/[^a-zA-Z\s]/',
	'',
	$input['name'] );

	//through an error if the give input is not match with the validated one
	if( $valid['name'] !== $input['name']){
		add_settings_error(
			'crud_text_string',
			'crud_name_text_error',
			'Please input the proper value. Only allows capital letters and lowercase letters',
			'error'
		);
	}

	//sanitize the 'fav_holiday' field
	$valid['fav_holiday'] = sanitize_text_field($input['fav_holiday']);

	//sanitize the 'beast_mode' field
	$valid['beast_mode'] = sanitize_text_field( $input['beast_mode'] );

	//finally return all of the form fields
	return $valid;
}
 

function create_section_and_fields(){

	$args = [
		'type' => 'string',
		'sanitize_callback' => 'sanitize_form_data',
		'default' => NULL
	];
	register_setting('crud_group_options', 'crud_options', $args);


	add_settings_section(
		'crud_section_index',
		'Crud Manager',
		'crud_section_text',
		'crud_manager'
	);

	//add a field to the section
	add_settings_field(
		'crud_text_field_index',
		'Your Name',
		'crud_text_field',
		'crud_manager',
		'crud_section_index'
	);

	//add favourite holiday settings field
	add_settings_field(
		'crud_text_field_holiday',
		'Favourite Holiday',
		'crud_text_holiday_field',
		'crud_manager',
		'crud_section_index'
	);

	//add beast mode filed
	add_settings_field(
		'crud_text_field_beast_mode',
		'Enable Beast Mode?',
		'crud_text_beast_mode_field',
		'crud_manager',
		'crud_section_index'
	);


}

function crud_section_text(){
	echo '<p> This is crud section</p>';
}

function crud_text_field(){

	//echo '<input type="text" placeholder="Entery Your Full Name">';
	// get option 'text_string' value from the database
	$options = get_option( 'crud_options' );
	$name = $options['name'];
	// echo the field
	echo "<input id='name' name='crud_options[name]'
	type='text' value='" . esc_attr( $name ) . "'/>";
}


function crud_text_beast_mode_field(){
	
	//get the option first
	$options = get_option('crud_options', ['beast_mode' => 'disabled']);
	$beast_mode = $options['beast_mode'];

	$items = ['enabled', 'disabled'];

	foreach( $items as $item ){
		echo '<label><input '. checked($beast_mode, $item, false) .' type="radio" name="crud_options[beast_mode]" value="'. esc_html($item) .'"/> '. esc_html($item) .' </label> <br>';
	}
}

function crud_text_holiday_field(){

	//if not set the fav_holiday set to 'Halloween'
	$options = get_option('crud_options', ['fav_holiday' => 'Halloween']);

	$fav_holiday = $options['fav_holiday'];

	$items = ['Halloween', 'Christmas', 'Eid Day', 'Holy', 'New Years'];

	echo '<select id="fav_holiday" name="crud_options[fav_holiday]">';
	     
	     foreach( $items as $item ){
			$selected = false;

			if($item == $fav_holiday) {
				$selected = true;
			}

			// echo '<option value="'. $item .'" '. ($selected) ? "selected" : "" .'>'. esc_html($item) .'</option>';

			echo "<option value='" .$item. "' " . selected( $fav_holiday, $item, false ).">" . esc_html( $item ) .
			"</option>";
		 }

	echo '</select>';
}
// function crud_text_username(){
// 	$options = get_option('crud_options');

// }



add_action('admin_init', 'create_section_and_fields');


