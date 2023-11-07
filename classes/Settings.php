<?php

namespace TravelSportsPro;

class Settings
{
    public function __construct()
    {
        add_action('admin_init', array($this, 'init'));
        add_action('admin_menu', array($this, 'initPage'));
    }
    public function Init()
    {
        register_setting( 'tsp_api_settings', 'tsp_api_url');
        register_setting( 'tsp_api_settings', 'tsp_api_key');
        register_setting( 'tsp_api_settings', 'tsp_api_version');
        register_setting( 'tsp_api_settings', 'tsp_private_lesson_section');
        register_setting( 'tsp_api_settings', 'tsp_random_url_parameter');      
        register_setting( 'tsp_api_settings', 'tsp_nav_item_background');
        register_setting( 'tsp_api_settings', 'tsp_nav_item_color');
        register_setting( 'tsp_api_settings', 'tsp_active_item_background');
        register_setting( 'tsp_api_settings', 'tsp_active_item_color');	
		// Dashboard Menu
    	register_setting( 'tsp_api_settings', 'show_dashboard_menu');
    	register_setting( 'tsp_api_settings', 'show_profile_menu');
    	register_setting( 'tsp_api_settings', 'show_payments_menu');
		register_setting( 'tsp_api_settings', 'show_calendar_menu');
    	register_setting( 'tsp_api_settings', 'show_cart_menu');
   		register_setting( 'tsp_api_settings', 'show_sales_items_menu');
        register_setting( 'tsp_api_settings', 'show_register_menu');
    	// Dropdown Menu
    	register_setting( 'tsp_api_settings', 'dropdown_show_profile_menu');
    	register_setting( 'tsp_api_settings', 'dropdown_show_addrelated_menu');
    	register_setting( 'tsp_api_settings', 'dropdown_show_register_menu');
		register_setting( 'tsp_api_settings', 'dropdown_show_calendar_menu');
    	register_setting( 'tsp_api_settings', 'dropdown_show_sales_items_menu');
    	register_setting( 'tsp_api_settings', 'dropdown_show_finance_menu');
    	register_setting( 'tsp_api_settings', 'dropdown_show_charges_menu');
    	register_setting( 'tsp_api_settings', 'dropdown_show_purchases_menu');
    	register_setting( 'tsp_api_settings', 'dropdown_show_changepassword_menu');
    	register_setting( 'tsp_api_settings', 'dropdown_show_giftcards_menu');
    	register_setting( 'tsp_api_settings', 'dropdown_show_logout_menu');
		// Mobile Menu
    	register_setting( 'tsp_api_settings', 'mobile_show_calendar_menu');
		register_setting( 'tsp_api_settings', 'mobile_show_sales_items_menu');
    	register_setting( 'tsp_api_settings', 'mobile_show_programs_menu');
    	register_setting( 'tsp_api_settings', 'mobile_show_charges_menu');
    	register_setting( 'tsp_api_settings', 'mobile_show_purchases_menu');
    	register_setting( 'tsp_api_settings', 'mobile_show_private_lessons_menu');
		register_setting( 'tsp_api_settings', 'mobile_show_giftcards_menu');
    }
    public function initPage()
    {
        add_options_page(
            'Travel Sports Pro Settings',
            'TSP Settings',
            'manage_options',
            'tsp-settings',
            array($this, 'setPage')
        );
    }
    public function setPage()
    {
        if (!empty($_POST['clear_cache'])) {
            delete_expired_transients(true);
            $tsp_classes_list = App::GetApi()->GetList("classes/list");
            set_transient('tsp_classes_list', $tsp_classes_list, 6 * HOUR_IN_SECONDS);
        }
?>
<div class="wrap">
    <script type="text/javascript" >
        jQuery(document).ready(function($) {
            $('.tsp_color_picker').wpColorPicker();			
            // Handle tabs
            $('.tsp-tab').on('click', function() {
                $('.tsp-tab-content').hide();
                $('.tsp-tab').removeClass('active');
                $(this).addClass('active');
                //alert($(this).data('target'));
                $($(this).data('target')).show();
            });          
            // Initialize with first tab active
            $('.tsp-tab').first().trigger('click');
        });
    </script>
    <h1>Settings - Travel Sports Pro</h1>
    <form method="post" action="options.php">
        <?php settings_fields('tsp_api_settings'); ?>
        <?php do_settings_sections('tsp_api_settings'); ?>
		<h2>
		Signup for Travel Sports Pro.	
		</h2>
		<p>To use features of Travel Sports Pro, you'll need to <a href="https://travelsportspro.com/free-trial/" target="_blank">signup for an account</a> and get your API Key to iniatiate the WP Plugin.
		</p>
        <!-- API Settings -->
        <table class="form-table">
            <tr valign="top">
                <th scope="row">TSP Client Admin URL</th>
                <td><input type="text" name="tsp_api_url" value="<?php echo esc_attr( get_option('tsp_api_url') ); ?>" placeholder="https://clients.travelsportspro.com/" style="min-width:360px"/></td>
            </tr>
            <tr valign="top">
                <th scope="row">TSP Client API Key</th>
                <td><input type="text" name="tsp_api_key" value="<?php echo esc_attr( get_option('tsp_api_key') ); ?>" style="min-width:360px"/></td>
            </tr>
			<tr valign="top">
                <th scope="row">TSP API Version</th>
                <td>
                    <select name="tsp_api_version">
                        <?php foreach(App::GetApi()->GetApiVersionList() as $k=>$v): echo '<option value="'.$v.'" '.((get_option('tsp_api_version') == $v) ? 'selected="selected"' : '').'>'.$v.'</option>'; endforeach; ?>
                    </select>
                </td>
            </tr>
        </table>
        <!-- Tabs Navigation -->
        <ul class="tsp-tabs">
			<li class="tsp-tab" data-target="#front-menu-settings">Menu Settings</li>
            <li class="tsp-tab" data-target="#brand-settings">Brand Settings</li>
            <li class="tsp-tab" data-target="#widgets">Widgets</li>
			<li class="tsp-tab" data-target="#additional">Additional Settings</li>
        </ul>
		<!-- Dashboard Menu Settings Tab -->
		<div id="front-menu-settings" class="tsp-tab-content">
		<h2>Dashboard Menu</h2>
		<p>Check the menu items you want to display in your dashboard menu</p>    
		<table class="form-table">
        <tr valign="top">
            <th scope="row">Show "Dashboard" in Menu</th>
            <td><input type="checkbox" name="show_dashboard_menu" value="1" <?php checked(1, get_option('show_dashboard_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Profile" in Menu</th>
            <td><input type="checkbox" name="show_profile_menu" value="1" <?php checked(1, get_option('show_profile_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Payments" in Menu</th>
            <td><input type="checkbox" name="show_payments_menu" value="1" <?php checked(1, get_option('show_payments_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Cart" in Menu</th>
            <td><input type="checkbox" name="show_cart_menu" value="1" <?php checked(1, get_option('show_cart_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Sales Items" in Menu</th>
            <td><input type="checkbox" name="show_sales_items_menu" value="1" <?php checked(1, get_option('show_sales_items_menu'), true); ?> /></td>
        </tr>
		<tr valign="top">
            <th scope="row">Show "Calendar" in Menu</th>
            <td><input type="checkbox" name="show_calendar_menu" value="1" <?php checked(1, get_option('show_calendar_menu'), true); ?> /></td>
        </tr>	
		<tr valign="top">
            <th scope="row">Show "Register" in Menu</th>
            <td><input type="checkbox" name="show_register_menu" value="1" <?php checked(1, get_option('show_register_menu'), true); ?> /></td>
        </tr>
        </table>	
        <!-- Dropdown Menu Items -->
		<h2>Dashboard Right Dropdown</h2>
		<p>Check the menu items you want to display in your dropdown menu on the right side of your dashboard</p>
		<table class="form-table">
        <tr valign="top">
            <th scope="row">Show "Profile" in Dropdown Menu</th>
            <td><input type="checkbox" name="dropdown_show_profile_menu" value="1" <?php checked(1, get_option('dropdown_show_profile_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Add Related Player" in Dropdown Menu</th>
            <td><input type="checkbox" name="dropdown_show_addrelated_menu" value="1" <?php checked(1, get_option('dropdown_show_addrelated_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Register" in Dropdown Menu</th>
            <td><input type="checkbox" name="dropdown_show_register_menu" value="1" <?php checked(1, get_option('dropdown_show_register_menu'), true); ?> /></td>
        </tr>
		<tr valign="top">
            <th scope="row">Show "Calendar" in Menu</th>
            <td><input type="checkbox" name="dropdown_show_calendar_menu" value="1" <?php checked(1, get_option('dropdown_show_calendar_menu'), true); ?> /></td>
        </tr>	
        <tr valign="top">
            <th scope="row">Show "Finance" in Dropdown Menu</th>
            <td><input type="checkbox" name="dropdown_show_finance_menu" value="1" <?php checked(1, get_option('dropdown_show_finance_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Charges" in Dropdown Menu</th>
            <td><input type="checkbox" name="dropdown_show_charges_menu" value="1" <?php checked(1, get_option('dropdown_show_charges_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Purchases" in Dropdown Menu</th>
            <td><input type="checkbox" name="dropdown_show_purchases_menu" value="1" <?php checked(1, get_option('dropdown_show_purchases_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Change Password" in Dropdown Menu</th>
            <td><input type="checkbox" name="dropdown_show_changepassword_menu" value="1" <?php checked(1, get_option('dropdown_show_changepassword_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Logout" in Dropdown Menu</th>
            <td><input type="checkbox" name="dropdown_show_logout_menu" value="1" <?php checked(1, get_option('dropdown_show_logout_menu'), true); ?> /></td>
        </tr>
        </table>
		<!-- Mobile Menu Items -->
		<h2>Mobile Menu Dropdown</h2>
		<p>Check the mobile menu items you want to display for mobile device users</p>
		<table class="form-table">
        <tr valign="top">
            <th scope="row">Show "Calendar" in Mobile Menu</th>
            <td><input type="checkbox" name="mobile_show_calendar_menu" value="1" <?php checked(1, get_option('mobile_show_calendar_menu'), true); ?> /></td>
        </tr>	
		<tr valign="top">
            <th scope="row">Show "Sales Items" in Mobile Menu</th>
            <td><input type="checkbox" name="mobile_show_sales_items_menu" value="1" <?php checked(1, get_option('mobile_show_sales_items_menu'), true); ?> /></td>
        </tr>
		<tr valign="top">
            <th scope="row">Show "Charges" in Mobile Menu</th>
            <td><input type="checkbox" name="mobile_show_charges_menu" value="1" <?php checked(1, get_option('mobile_show_charges_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Purchases" in Mobile Menu</th>
            <td><input type="checkbox" name="mobile_show_purchases_menu" value="1" <?php checked(1, get_option('mobile_show_purchases_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Gift Cards" in Mobile Menu</th>
            <td><input type="checkbox" name="mobile_show_giftcards_menu" value="1" <?php checked(1, get_option('mobile_show_giftcards_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Private Lessons" in Mobile Menu</th>
            <td><input type="checkbox" name="mobile_show_private_lessons_menu" value="1" <?php checked(1, get_option('mobile_show_private_lessons_menu'), true); ?> /></td>
        </tr>
        <tr valign="top">
            <th scope="row">Show "Programs" in Mobile Menu</th>
            <td><input type="checkbox" name="mobile_show_programs_menu" value="1" <?php checked(1, get_option('mobile_show_programs_menu'), true); ?> /></td>
        </tr>
        </table>
        </div>		
        <!-- Tab Contents -->
        <div id="brand-settings" class="tsp-tab-content">
			<h2>Button Colors</h2>
		    <p>Set the background and text colors for your menu items</p>   
			<table class="form-table">
                <tr valign="top">
                    <th scope="row">Nav Menu Item Background Color</th>
                    <td><input type="text" class="tsp_color_picker" name="tsp_nav_item_background" value="<?php echo esc_attr( get_option('tsp_nav_item_background') ); ?>" style="max-width:80px" placeholder="default"/></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Nav Menu Item Text Color</th>
                    <td><input type="text" class="tsp_color_picker" name="tsp_nav_item_color" value="<?php echo esc_attr( get_option('tsp_nav_item_color') ); ?>" style="max-width:80px" placeholder="default"/></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Active Menu Item Background Color</th>
                    <td><input type="text" class="tsp_color_picker" name="tsp_active_item_background" value="<?php echo esc_attr( get_option('tsp_active_item_background') ); ?>" style="max-width:80px" placeholder="default"/></td>
                </tr>
                <tr valign="top">
                    <th scope="row">Active Menu Item Text Color</th>
                    <td><input type="text" class="tsp_color_picker" name="tsp_active_item_color" value="<?php echo esc_attr( get_option('tsp_active_item_color') ); ?>" style="max-width:80px" placeholder="default"/></td>
                </tr>
            </table>
		</div>
        <div id="widgets" class="tsp-tab-content">
			<h2>Widget Settings</h2>
            Future widget settings
        </div>	
		<div id="additional" class="tsp-tab-content">
			<h2>Additional Settings / Options</h2>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">TSP Enable Private Lessons Section</th>
                    <td>
                        <select name="tsp_private_lesson_section">
                        <?php
                            echo '<option value="0" '.((get_option('tsp_private_lesson_section') == '0') ? 'selected="selected"' : '').'>No</option>';
                            echo '<option value="1" '.((get_option('tsp_private_lesson_section') == '1') ? 'selected="selected"' : '').'>Yes</option>';
                        ?>
                        </select>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">TSP Add Random URL Parameter (prevent caching)</th>
                    <td>
                        <select name="tsp_random_url_parameter">
                        <?php
                            echo '<option value="0" '.((get_option('tsp_random_url_parameter') == '0') ? 'selected="selected"' : '').'>No</option>';
                            echo '<option value="1" '.((get_option('tsp_random_url_parameter') == '1') ? 'selected="selected"' : '').'>Yes</option>';
                        ?>
                        </select>
                    </td>
                </tr>
            </table>
        </div>      
        <?php submit_button(); ?>
    </form>
</div>
<style>	
    /* Tab styles */
.tsp-tabs {
    list-style: none;
    padding: 0;
    border-bottom: 1px solid #ddd;
    margin-bottom: 20px 0;
}
.tsp-tab {
    display: inline-block;
    margin: 0 0 0 .5em;
    padding: 10px 20px;
    cursor: pointer;
    border: 1px solid #ddd;
    border-bottom: none;
    background-color: #e4e4e4;
	font-weight: 600;
}
.tsp-tab.active {
    background-color: #f1f1f1; 
    border-bottom-color: transparent;
    border-color: #ddd; 
    border-bottom: 2px solid transparent;
	margin-bottom: -2px;
}
.tsp-tab-content {
    display: none;
}
</style>
<?php
    }
}