<?php
namespace TravelSportsPro;

class Settings
{
    public function __construct()
    {
        add_action( 'admin_init', array( $this, 'Init' ) );
        add_action( 'admin_menu', array( $this, 'InitPage' ) );
    }
    
    public function Init()
    {
        register_setting( 'tsp_api_settings', 'tsp_api_url');
        register_setting( 'tsp_api_settings', 'tsp_api_key');
        register_setting( 'tsp_api_settings', 'tsp_api_version');
        register_setting( 'tsp_api_settings', 'tsp_private_lesson_section');
        register_setting( 'tsp_api_settings', 'tsp_random_url_parameter');
        //register_setting( 'tsp_api_settings', 'tsp_class_cache');
    }
    
    public function InitPage()
    {
        add_options_page(
            'Dance Studion Manager Settings', 
            'TSP Settings', 
            'manage_options', 
            'tsp-settings', 
            array( $this, 'SetPage' )
        );
    }
    
    public function SetPage()
    {

    if (!empty($_POST['clear_cache'])) {
        delete_expired_transients( true );
        $tsp_classes_list = App::GetApi()->GetList("classes/list");
        set_transient( 'tsp_classes_list', $tsp_classes_list, 6 * HOUR_IN_SECONDS );
    }
?>
    <div class="wrap">
<h1>TSP Plugin</h1>

<form method="post" action="options.php">
    <?php settings_fields( 'tsp_api_settings' ); ?>
    <?php do_settings_sections( 'tsp_api_settings' ); ?>
    <table class="form-table">
        <tr valign="top" >
        <th scope="row">TSP Url</th>
        <td><input type="text" name="tsp_api_url" value="<?php echo esc_attr( get_option('tsp_api_url') ); ?>" placeholder="https://clients.travelsportspro.com/" style="min-width:360px"/></td>
        </tr>
        <tr valign="top">
        <th scope="row">TSP Api Key</th>
        <td><input type="text" name="tsp_api_key" value="<?php echo esc_attr( get_option('tsp_api_key') ); ?>" style="min-width:360px"/></td>
        </tr>
        <tr valign="top">
        <th scope="row">TSP Api Version</th>
            <td>
                <select name="tsp_api_version">
                <?php
                    foreach(App::GetApi()->GetApiVersionList() as $k=>$v):
                        echo '<option value="'.$v.'" '.((get_option('tsp_api_version') == $v) ? 'selected="selected"' : '').'>'.$v.'</option>';
                    endforeach;
                ?>
                </select>
            </td>
        </tr>
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
        <th scope="row">TSP Add Random Url Parameter (prevent caching)</th>
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
    <?php submit_button(); ?>
</form>
</div>
    <?php
    }
}