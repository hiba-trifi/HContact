<?php
ob_start();
/*
Plugin Name: Contact Form
Plugin URI: http://contact-form.com
Description: This plugin adds a contact form to your WordPress site.
Version: 2.0
Author: Hiba 
Author URI: http://mycontactform.com
License: GPL2
*/
?>

<?php
// link style 
function my_plugin_styles()
{
	wp_enqueue_style('my-plugin-styles', plugins_url('css.css', __FILE__));
}
add_action('wp_enqueue_scripts', 'my_plugin_styles');


// Display the plugin in the Dashboard menu  
function add_dashboard_form_menu_item()
{
	add_menu_page(
		'Form',
		// Page title
		'Contact Form',
		// Menu title
		'manage_options',
		// Capability required to access the page
		'my-dashboard-form',
		// Menu slug
		'how_to_use', // Callback function to display the page
		// 'dashicons-email', // Icon URL
		plugin_dir_url(__FILE__) . 'forms.png',
		// Icon URL
		9 // Menu position
	);
}
add_action('admin_menu', 'add_dashboard_form_menu_item');
// Display submenu How to use

function how_to_use()
{
	echo '
				<div class="wrap">
					<h1>Welcome to My Plugin!</h1>
					<p>Thank you for installing My Plugin. This plugin allows you to easily create custom contact forms and manage the messages that you receive.</p>
					<h2>How to Use</h2>
					<p>To create a new contact form, simply add the shortcode <code>[dashboard-form]</code> to any page or post. This will display a form that visitors can use to contact you.</p>
					<p>To view messages that you have received, navigate to the "Received Messages" page in the dashboard menu. From there, you can view, delete, and export your messages as needed.</p>
					<h2>Customization</h2>
					<p>My Plugin is highly customizable. You can change the look and feel of your contact forms by modifying the CSS. You can also add custom fields to your forms using the plugins API.</p>
					<h2>Exporting Data</h2>
					<p>If you need to export your messages for backup or analysis, My Plugin makes it easy. Simply navigate to the "Received Messages" page in the dashboard menu, select the messages you want to export, and click the "Export" button.</p>
					<h2>Support</h2>
					<p>If you have any questions or issues with the plugin, please contact us at support@myplugin.com. Our support team is available 24/7 to assist you.</p>
				</div>';
}

// Form Function
function show_form_page()
{
	$options = get_option('my_plugin_options');
	$form_bkg_color = isset($options['form_bkg_color']) ? $options['form_bkg_color'] : '';
	$form_align = isset($options['form_align']) ? $options['form_align'] : '';
	$color = isset($options['form_color']) ? $options['form_color'] : '#ffffff';
	$bgcolor = isset($options['input_bg_color']) ? $options['input_bg_color'] : '#ffffff';
	$font = isset($options['form_font']) ? $options['form_font'] : 'Arial';
	$form_border_radius = isset($options['form_border_radius']) ? $options['form_border_radius'] : '';
	$form_border_color = isset($options['form_border_color']) ? $options['form_border_color'] : '#000000';
	$form_border_style = isset($options['form_border_style']) ? $options['form_border_style'] : 'solid';
	$form_border_width = isset($options['form_border_width']) ? $options['form_border_width'] : '';
	$form_align = isset($options['form_align']) ? $options['form_align'] : '';
	echo '
		<style>
			form {
				font-family: ' . $font . ', sans-serif;
			}
		</style>
		<div class="wrap" style="background-color:' . $form_bkg_color . ';padding:10%;border-radius:10px;">
			<form method="post" action="" style="padding:5%;border-radius:10px;text-align:' . $form_align . ';">
				<label for="subject">Subject :</label>
				<input  style="color:' . $color . ';background-color: ' . $bgcolor . ';border-radius:' . $form_border_radius . ';border-color:' . $form_border_color . ';border-style:' . $form_border_style . '; border-width:' . $form_border_width . ';border-width:' . $form_border_width . ';" type="text" id="subject" name="subject" ><br><br>
				<label for="first_name">First Name:</label>
				<input  style="color:' . $color . ';background-color: ' . $bgcolor . ';border-radius:' . $form_border_radius . ';border-color:' . $form_border_color . ';border-style:' . $form_border_style . ';border-width:' . $form_border_width . ';" type="text" id="first_name" name="first_name"><br><br>
				<label for="last_name">Last Name:</label>
				<input  style="color:' . $color . ';background-color: ' . $bgcolor . ';border-radius:' . $form_border_radius . ';border-color:' . $form_border_color . ';border-style:' . $form_border_style . ';border-width:' . $form_border_width . ';" type="text" id="last_name" name="last_name"><br><br>
				<label for="email">Email:</label>
				<input  style="color:' . $color . ';background-color: ' . $bgcolor . ';border-radius:' . $form_border_radius . ';border-color:' . $form_border_color . ';border-style:' . $form_border_style . ';border-width:' . $form_border_width . ';" type="email" id="email" name="email"><br><br>
				<label for="message">Message:</label><br>
				<textarea id="message" style="color:' . $color . ';background-color: ' . $bgcolor . ';border-radius:' . $form_border_radius . ';border-color:' . $form_border_color . ';border-style:' . $form_border_style . ';border-width:' . $form_border_width . ';" name="message" rows="5" cols="20"></textarea><br><br>
				<input type="submit" name="submit" style="padding:5%;color:;border-radius:10px;border:none;background-color:black;color:' . $bgcolor . ';" value="Send">				
				</form>
				
			<div id="error-message"></div>
			</div>';
}



// Display submenu received messages
function add_dashboard_submenu_item()
{
	add_submenu_page(
		'my-dashboard-form',
		// parent slug
		'received',
		// page title
		'Received messages',
		// menu title
		'manage_options',
		// capability required to access the page
		'how-to-use',
		// menu slug
		'show_received_page' // callback function to display the page
	);
}
add_action('admin_menu', 'add_dashboard_submenu_item');
function show_received_page()
{
	global $wpdb;

	$table_name = $wpdb->prefix . 'contact_form';
	$results = $wpdb->get_results("SELECT * FROM $table_name");
	echo '<h1>Received Messages</h1>';
	// Display the messages in a table
	if (empty($results)) {
		echo '<p>No messages have been received yet.</p>';
	} else {
		echo '<form method="post"><input type="submit" name="export_sql" class="button" value="Export SQL"></form>';
		echo '<table class="wp-list-table widefat striped">';
		echo '<thead><tr><th>First Name</th><th>Last Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Action</th></tr></thead>';
		echo '<tbody>';
		foreach ($results as $row) {
			echo '<tr>';
			echo '<td>' . $row->firstname . '</td>';
			echo '<td>' . $row->lastname . '</td>';
			echo '<td>' . $row->email . '</td>';
			echo '<td>' . $row->subject . '</td>';
			echo '<td>' . $row->message . '</td>';
			echo '<td><form method="post"><input type="hidden" name="message_id" value="' . $row->id . '"><input type="submit" name="delete_message" class="button" value="Delete"></form></td>';
			echo '</tr>';
		}
		echo '</tbody></table>';
	}
	// Delete a message if the "Delete" button is clicked
	if (isset($_POST['delete_message'])) {
		$message_id = intval($_POST['message_id']);
		$wpdb->delete($table_name, array('id' => $message_id));
		echo '<div class="notice notice-success"><p>The message has been deleted.</p></div>';
	}
}


// Display submenu Customize Form
function my_plugin_settings_page()
{
	?>
	<div class="wrap" style="display: flex;justify-content:space-around;">
		<div style="background-color: white;border-radius:10px;padding:4%;">
			<h1>Customize Form</h1>
			<form method="post" action="options.php" style="padding:5%;border-radius:10px;">
				<?php
				// output security fields for the registered setting "my_plugin_options"
				settings_fields('my_plugin_options');
				// output setting sections and their fields
				do_settings_sections('my_plugin_options');
				// submit button
				submit_button('Save Changes');
				?>
			</form>
		</div>

		<?php
		echo "<div style='background-color:white;padding:4%;border-radius:10px;'>
	 <h1>Preview changes</h1>";
		show_form_page();
		echo "</div></div>";
}

function add_dashboard_submenu_item1()
{
	add_submenu_page(
		'my-dashboard-form',
		// parent slug
		'customize ',
		// page title
		'customize  Form',
		// menu title
		'manage_options',
		// capability required to access the page
		'customize-form',
		// menu slug
		'my_plugin_settings_page' // callback function to display the page
	);
}
add_action('admin_menu', 'add_dashboard_submenu_item1');

function register_my_plugin_settings()
{
	// register a new setting for "my_plugin_options" page
	register_setting('my_plugin_options', 'my_plugin_options');

	// add a new section to the "my_plugin_options" page
	add_settings_section(
		'my_plugin_section',
		// ID
		'Form Customization',
		// title
		'my_plugin_section_callback',
		// callback function
		'my_plugin_options' // page
	);

	// add form customization fields
	add_settings_field(
		'form_bkg_color',
		// ID
		'Form Background Color',
		// title
		'form_bkg_color_callback',
		// callback function
		'my_plugin_options',
		// page
		'my_plugin_section' // section
	);

	add_settings_field(
		'form_align',
		// ID
		'Form Background Color',
		// title
		'form_align_callback',
		// callback function
		'my_plugin_options',
		// page
		'my_plugin_section' // section
	);

	add_settings_field(
		'form_color',
		// ID
		'Form Color',
		// title
		'form_color_callback',
		// callback function
		'my_plugin_options',
		// page
		'my_plugin_section' // section
	);

	add_settings_field(
		'form_font',
		// ID
		'Form Font',
		// title
		'form_font_callback',
		// callback function
		'my_plugin_options',
		// page
		'my_plugin_section' // section
	);

	add_settings_field(
		'input_bg_color',
		// ID
		'Input Background Color',
		// title
		'input_bg_color_callback',
		// callback function
		'my_plugin_options',
		// page
		'my_plugin_section' // section
	);

	add_settings_field(
		'form_border_radius',
		// ID
		'Border Radius',
		// title
		'form_border_radius_callback',
		// callback function
		'my_plugin_options',
		// page
		'my_plugin_section' // section
	);

	add_settings_field(
		'form_border_color',
		// ID
		'Border Color',
		// title
		'form_border_color_callback',
		// callback function
		'my_plugin_options',
		// page
		'my_plugin_section' // section
	);

	add_settings_field(
		'form_border_style',
		// ID
		'Border Style',
		// title
		'form_border_style_callback',
		// callback function
		'my_plugin_options',
		// page
		'my_plugin_section' // section
	);

	add_settings_field(
		'form_border_width',
		// ID
		'Border width',
		// title
		'form_border_width_callback',
		// callback function
		'my_plugin_options',
		// page
		'my_plugin_section' // section
	);
}


// Calback functions 
function my_plugin_section_callback()
{
	echo 'customize  the look and feel of your form here.';
}

function form_bkg_color_callback()
{
	$options = get_option('my_plugin_options');
	$bkg_color = isset($options['form_bkg_color']) ? $options['form_bkg_color'] : '';
	echo "<input type='color' name='my_plugin_options[form_bkg_color]' value='$bkg_color'/>";
}

function form_align_callback()
{
	$options = get_option('my_plugin_options');
	$form_align = isset($options['form_align']) ? $options['form_align'] : 'solid';
	$form_align_type = array('left', 'right', 'center', 'justify');
	echo '<select name="my_plugin_options[form_align]">';
	foreach ($form_align_type as $style) {
		$selected = ($style == $form_align) ? 'selected' : '';
		echo "<option value='$style' $selected>$style</option>";
	}
	echo '</select>';
}
function form_color_callback()
{
	$options = get_option('my_plugin_options');
	$color = isset($options['form_color']) ? $options['form_color'] : '#ffffff';
	echo "<input type='color' name='my_plugin_options[form_color]' value='$color'/>";
}

function form_font_callback()
{
	$options = get_option('my_plugin_options');
	$font = isset($options['form_font']) ? $options['form_font'] : 'Arial';
	echo "<input type='text' name='my_plugin_options[form_font]' value='$font' />";
}


function input_bg_color_callback()
{
	$options = get_option('my_plugin_options');
	$bgcolor = isset($options['input_bg_color']) ? $options['input_bg_color'] : '#ffffff';
	echo "<input type='color' name='my_plugin_options[input_bg_color]' value='$bgcolor' />";
}

function form_border_radius_callback()
{
	$options = get_option('my_plugin_options');
	$border_radius = isset($options['form_border_radius']) ? $options['form_border_radius'] : '';
	echo "<input type='text' name='my_plugin_options[form_border_radius]' value='$border_radius' />";
}

function form_border_color_callback()
{
	$options = get_option('my_plugin_options');
	$border_color = isset($options['form_border_color']) ? $options['form_border_color'] : '';
	echo "<input type='color' name='my_plugin_options[form_border_color]' value='$border_color' />";
}

function form_border_style_callback()
{
	$options = get_option('my_plugin_options');
	$border_style = isset($options['form_border_style']) ? $options['form_border_style'] : 'solid';
	$border_styles = array('none', 'solid', 'dotted', 'dashed', 'double', 'groove', 'ridge', 'inset', 'outset');
	echo '<select name="my_plugin_options[form_border_style]">';
	foreach ($border_styles as $style) {
		$selected = ($style == $border_style) ? 'selected' : '';
		echo "<option value='$style' $selected>$style</option>";
	}
	echo '</select>';
}
function form_border_width_callback()
{
	$options = get_option('my_plugin_options');
	$border_width = isset($options['form_border_width']) ? $options['form_border_width'] : '';
	echo "<input type='width' name='my_plugin_options[form_border_width]' value='$border_width' />";
}

add_action('admin_init', 'register_my_plugin_settings');


// Display the form with a shortcode
function display_dashboard_form_shortcode()
{
	ob_start();
	show_form_page();
	$output = ob_get_clean();
	return $output;
}
add_shortcode('dashboard-form', 'display_dashboard_form_shortcode');


// Creat database table when activating the plugin
function create_database()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'contact_form';
	$sql = "CREATE TABLE IF NOT EXISTS $table_name (
				id INT(11) NOT NULL AUTO_INCREMENT,
				subject VARCHAR(50) NOT NULL,
				firstname VARCHAR(50) NOT NULL,
				lastname VARCHAR(50) NOT NULL,
				email VARCHAR(100) NOT NULL,
				message TEXT NOT NULL,
				PRIMARY KEY (id)
			) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;";
	require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
	dbDelta($sql);
}

// Activation
function activation_functions()
{
	create_database();
}
register_activation_hook(__FILE__, 'activation_functions');


// Delete database table when deactivating the plugin
function delete_contact_form_table()
{
	global $wpdb;
	$table_name = $wpdb->prefix . 'contact_form';
	$sql = "DROP TABLE IF EXISTS $table_name;";
	$wpdb->query($sql);
}
register_deactivation_hook(__FILE__, 'delete_contact_form_table');






//After submit entering form content to database
if (isset($_POST['submit'])) {
	global $wpdb;
	$table_name = $wpdb->prefix . 'contact_form';

	// Sanitize input data
	$subject = sanitize_text_field($_POST['subject']);
	$first_name = sanitize_text_field($_POST['first_name']);
	$last_name = sanitize_text_field($_POST['last_name']);
	$email = sanitize_email($_POST['email']);
	$message = sanitize_text_field($_POST['message']);

	// Check if any field is empty
	if (empty($subject) || empty($first_name) || empty($last_name) || empty($email) || empty($message)) {
		echo '<div class="notice notice-error is-dismissible"><p>Please fill out all fields before submitting the form.</p></div>';
	} else {
		// Insert data into the database
		$wpdb->insert(
			$table_name,
			array(
				'subject' => $subject,
				'firstname' => $first_name,
				'lastname' => $last_name,
				'email' => $email,
				'message' => $message
			),
			array('%s', '%s', '%s', '%s', '%s')
		);
		echo '<div class="notice notice-success is-dismissible"><p>Your message has been sent successfully!</p></div>';
	}
}



// export table 
if (isset($_POST['export_sql'])) {
	// Generate the SQL file
	$filename = 'contact_form_' . date('YmdHis') . '.sql';
	header('Content-type: text/sql');
	header('Content-Disposition: attachment; filename=' . $filename);

	$table_name = 'wp_contact_form';
	$sql = "SELECT * INTO OUTFILE '$filename' FROM $table_name";
	$wpdb->query($sql);
	exit;
}