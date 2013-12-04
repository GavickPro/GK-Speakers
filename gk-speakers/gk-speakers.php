<?php
/*
Plugin Name: GK Speakers
Plugin URI: http://www.gavick.com/
Description: A simple plugin for Speakers - Custom Post Type from Fest Theme.
Version: 1.0
Author: GavickPro
Author URI: http://www.gavick.com/
*/


// CONSTANTS
if( !defined( 'GK_SPK_BASE_FILE' ) )		define( 'GK_SPK_BASE_FILE', __FILE__ );
if( !defined( 'GK_SPK_BASE_DIR' ) ) 		define( 'GK_SPK_BASE_DIR', dirname( GK_SPK_BASE_FILE ) );
if( !defined( 'GK_SPK_PLUGIN_URL' ) ) 		define( 'GK_SPK_PLUGIN_URL', plugin_dir_url( __FILE__ ) );

// Specify Hooks/Filters
register_activation_hook(__FILE__, 'gk_speakers_activate');
register_deactivation_hook(__FILE__, 'gk_speakers_deactivate');
add_action('admin_init', 'gk_speakers_options_init_fn' );
add_action('admin_menu', 'gk_speakers_options_add_page_fn');

/**
 * Define default settings and call gavern_create_post_type function
 */
function gk_speakers_activate() {
	$arr = array(
		"name" => "gavern_speakers",
		"cpt_name" => "Speakers",
		"name_singular" => "Speaker",
		"add_new" => "Add New Speaker", 
		"speakers_tax" => "category",
		"speakers_slug" => "speaker", 
		"speakers_position" => 5, 
		"speakers_comments" => "Enabled", 
		"category_full" => "Enabled"
	);

	update_option('speakers_options', $arr);
	gk_speakers_create_post_type();
	flush_rewrite_rules();
}

// Deactivation of the plugin
function gk_speakers_deactivate() {
	flush_rewrite_rules();
}

// Register GK Speakers settings. Add the settings section and settings fields
function gk_speakers_options_init_fn(){
	register_setting('speakers_options', 'speakers_options', 'gk_speakers_options_validate' );
	add_settings_section('section_labels', 'Custom Post Type Labels settings', 'gk_speakers_section_one', __FILE__);
	add_settings_field('custom_posts', 'Custom Posts', 'gk_speakers_cpt_name', __FILE__, 'section_labels');
	add_settings_field('custom_cpt_name', 'Custom Post Name (plural)', 'gk_speakers_displayed_name', __FILE__, 'section_labels');
	add_settings_field('custom_name_singular', 'Custom Post Name (singular)', 'gk_speakers_singular_name', __FILE__, 'section_labels');
	add_settings_field('custom_add_new', 'Add new text', 'gk_speakers_addnew_text', __FILE__, 'section_labels');
	add_settings_section('section_arguments', 'Custom Post Type other settings', 'gk_speakers_section_two', __FILE__);
	add_settings_field('speakers_tax', 'Custom Post Taxonomy (existing)', 'gk_speakers_taxonomy', __FILE__, 'section_arguments');
	add_settings_field('speakers_pos', 'Custom Post Dashboard Position', 'gk_speakers_position', __FILE__, 'section_arguments');
	add_settings_field('speakers_slu', 'Custom Post Slug', 'gk_speakers_slug', __FILE__, 'section_arguments');
	add_settings_section('section_options', 'Template Options', 'gk_speakers_section_three', __FILE__);
	add_settings_field('speakers_comment', 'Comments on Single Custom Post', 'gk_speakers_comments', __FILE__, 'section_options');
	add_settings_field('category_width', 'Full width Custom Post category', 'gk_category_full', __FILE__, 'section_options');
}

// Add sub page to the Settings Menu
function gk_speakers_options_add_page_fn() {
	add_options_page('GK Speakers Options', 'GK Speakers', 'administrator', __FILE__, 'gk_speakers_options_page');
}


// Short descriptions of the options sections
function  gk_speakers_section_one() {
	echo '<p>You can set following options connected with the custom post type labels.</p>';
}

function  gk_speakers_section_two() {
	echo '<p>You can set following options connected with Custom Post Type support.</p>';
}

function  gk_speakers_section_three() {
	echo '<p>You can set following options connected with Custom Post Type template.</p>';
}

// Settins fields
function gk_speakers_cpt_name() {
	$options = get_option('speakers_options');
	$name = esc_attr( $options['name']);
	echo "<input id='single_name' name='speakers_options[name]' size='40' type='text' value='$name' />";
}

function gk_speakers_displayed_name() {
	$options = get_option('speakers_options');
	$cpt_name = esc_attr( $options['cpt_name']);
	echo "<input id ='cat_name' name='speakers_options[cpt_name]' size='40' type='text' value='$cpt_name' />";
}

function gk_speakers_singular_name() {
	$options = get_option('speakers_options');
	$name_singular = esc_attr( $options['name_singular']);
	echo "<input name='speakers_options[name_singular]' size='40' type='text' value='$name_singular' />";
}

function gk_speakers_addnew_text() {
	$options = get_option('speakers_options');
	$add_new = esc_attr( $options['add_new']);
	echo "<input name='speakers_options[add_new]' size='40' type='text' value='$add_new' />";
}

function gk_speakers_taxonomy() {
	$options = get_option('speakers_options');
	$speakers_tax = esc_attr( $options['speakers_tax']);
	echo "<input name='speakers_options[speakers_tax]' size='40' type='text' value='$speakers_tax' />";
}

function gk_speakers_position() {
	$options = get_option('speakers_options');
	$items = array(5, 10, 15, 20, 25, 60, 65, 70, 75, 80, 100);
	echo "<select id='speakers_position' name='speakers_options[speakers_position]'>";
	foreach($items as $item) {
		$selected = ($options['speakers_position']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select>";
}

function gk_speakers_slug() {
	$options = (array) get_option('speakers_options');
	$speakers_slug = esc_attr( $options['speakers_slug']);
	echo "<input name='speakers_options[speakers_slug]' size='40' type='text' value='$speakers_slug' />";
}

function gk_speakers_comments() {
	$options = get_option('speakers_options');
	$items = array("Enabled", "Disabled");
	echo "<select id='comments_toogle' name='speakers_options[speakers_comments]'>";
	foreach($items as $item) {
		$selected = ($options['speakers_comments']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select>";
}

function gk_category_full() {
	$options = get_option('speakers_options');
	$items = array("Enabled", "Disabled");
	echo "<select id='category_width' name='speakers_options[category_full]'>";
	foreach($items as $item) {
		$selected = ($options['category_full']==$item) ? 'selected="selected"' : '';
		echo "<option value='$item' $selected>$item</option>";
	}
	echo "</select>";
}


// Display the admin options page
function gk_speakers_options_page() {
$tmp = get_option('speakers_options');
?>
	<div class="wrap">
		<div class="icon32" id="icon-options-general"><br></div>
		<h2>GK Speakers Options</h2>
		These options are related with the GK Speakers plugin from GavickPro Fest Theme.
		<form action="options.php" method="post">
			<?php settings_fields('speakers_options'); ?>
			<?php do_settings_sections(__FILE__); ?>
			<p class="submit">
				<input name="Submit" type="submit" class="button-primary" value="<?php esc_attr_e('Save Changes'); ?>" />
			</p>
		</form>
		
		<div class="gk-info">
			<p>Save your changes and then you have to rename files from Fest directory:</p>
			<ul>
				<li>category-speakers.php rename to category-<span class="gk-keydown"><?php echo $tmp['cpt_name'];?></span>.php</li>
				<li>single-gavern_speakers.php rename to single-<span class="gk-keydown2"><?php echo $tmp['name'];?></span>.php</li>
			</ul>
			<div id="gk-additional-info">
				<p>If you are using WPML Plugin, remember that after translating your category to other languages, you have to duplicate your category-speakers.php file and name it as category-your_translated_category_slug.php (If you have e.g. 4 languages, you should have 4 category-slug files)</p>
			</div>
		</div>
	</div>
<?php
}

// Validate user data for some/all of input fields
function gk_speakers_options_validate($input) {
	// Check our textboxes option field contains no HTML tags - if so strip them out
	$input['cpt_name'] =  wp_filter_nohtml_kses($input['cpt_name']);
	$input['name'] = wp_filter_nohtml_kses($input['name']);
	$input['name_singular'] = wp_filter_nohtml_kses($input['name_singular']);
	$input['add_new'] = wp_filter_nohtml_kses($input['add_new']);
	$input['speakers_tax'] = wp_filter_nohtml_kses($input['speakers_tax']);
	$input['speakers_slug'] = wp_filter_nohtml_kses($input['speakers_slug']);
	
	return $input; // return validated inputs
}


/**
 *
 * Defines the custom post type with specified options
 *
 **/

function gk_speakers_create_post_type() {
	$options = get_option( 'speakers_options' );
	$taxonomy = $options[ 'speakers_tax' ];
	$post_name = strtolower($options[ 'name' ]);
	$slug = $options[ 'speakers_slug' ];
	
 	// Custom Post Type Labels
    $labels = array(
        'name'			=> $options[ 'cpt_name' ],
        'singular_name' => $options[ 'name_singular' ],
        'add_new_item'  => $options[ 'add_new' ]
    );	
 	
 	// Custom Post Type Supports
    $args = array(
        'labels'		=> $labels,
        'taxonomies' 	=> array($taxonomy),
		'menu_position' => intval($options['speakers_position']),
        'public' 		=> true,
        'rewrite' 		=> array('slug' => $slug),
        'has_archive' 	=> true,
		'supports' 		=> array('title', 'editor', 'thumbnail', 'custom-fields', 'comments')
    );
    
    //register the "Speakers" custom post type
    register_post_type($post_name, $args);
}
add_action('init', 'gk_speakers_create_post_type');



// include spcript.js file with keydown jquery support
function gk_speakers_admin_js() {
    wp_register_script('gk_speakers_admin_js', WP_CONTENT_URL . '/plugins/gk-speakers/js/script.js',  array('jquery'));
    wp_enqueue_script('gk_speakers_admin_js');
}
add_action('admin_enqueue_scripts', 'gk_speakers_admin_js');

// include CSS styles for the options page
function gk_speakers_styles() { 
	wp_register_style( 'gk-speakers-style', WP_CONTENT_URL . '/plugins/gk-speakers/css/style.css', false);
	wp_enqueue_style( 'gk-speakers-style' );
}
add_action('admin_enqueue_scripts', 'gk_speakers_styles');

// Function to correct problem when you create new post type and add categories to your custom post types and you can't find your custom post when you use category or tag for select it.
function gk_speakers_cpt_category(  $qry ) {

    if ( $qry->is_category && empty($qry->query_vars['post_type'])) {
        $qry->query_vars['post_type'] = 'any';
    }
    
    if ( $qry->is_tag && empty($qry->query_vars['post_type'])) {
        $qry->query_vars['post_type'] = 'any';
    }

}
add_action( 'pre_get_posts', 'gk_speakers_cpt_category' );
