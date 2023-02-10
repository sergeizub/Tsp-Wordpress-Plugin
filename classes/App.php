<?php
namespace TravelSportsPro;

class App
{
    protected static $api;
    protected static $client;
    protected static $template;
    protected static $settings;
    protected static $error;
    protected static $emailer;

    public function __construct()
    {
        self::$error = new Error();
        self::$api = new Api();
        self::$client = new Client();
        self::$template = new Template();
        self::$settings = new Settings();
        self::$emailer = new Emailer();

        add_action('admin_enqueue_scripts', function ($hook) {
                wp_enqueue_style('tsp_admin', plugins_url('../css/admin.css',__FILE__ ));
                wp_enqueue_style('wp-color-picker');
                wp_enqueue_script('wp-color-picker');
            });

        add_action('wp_enqueue_scripts', function ($hook) {
                wp_register_style( 'tsp_css_bootstrap', plugins_url('../assets/bootstrap-3.3.7/css/bootstrap.min.css',__FILE__) );
                wp_register_style( 'tsp_datetimepicker', plugins_url('../assets/bootstrap-3.3.7/css/bootstrap-datetimepicker.min.css',__FILE__) );
                wp_register_style( 'tsp_fontawesome', plugins_url('../assets/font-awesome-4.7.0/css/font-awesome.min.css',__FILE__) );
                wp_register_style( 'tsp_style', plugins_url('../assets/css/style.css?v='.rand(),__FILE__ ) );
                wp_register_style( 'tsp_fullcalendar', plugins_url('../assets/fullcalendar-3.9.0/fullcalendar.min.css',__FILE__) );

                wp_register_script( 'tsp_js_bootstrap', plugins_url('../assets/bootstrap-3.3.7/js/bootstrap.min.js',__FILE__) , array('jquery'));
                wp_register_script( 'tsp_momentjs', plugins_url('../assets/js/moment.js/2.20.1/moment.min.js',__FILE__) , array('jquery'));
                wp_register_script( 'tsp_signature_pad', plugins_url('../assets/js/signature_pad/index.js',__FILE__ ) , array('jquery'), time());
                wp_register_script( 'tspfunctionjs', plugins_url('../assets/js/functions.js',__FILE__ ) , array('jquery') , time());

                wp_localize_script( 'tspfunctionjs', 'tspajax',
                    array(
                        'url' => admin_url('admin-ajax.php')
                    )
                );
                wp_register_script( 'tsp_datetimepicker', plugins_url('../assets/bootstrap-3.3.7/js/bootstrap-datetimepicker.min.js',__FILE__) , array('jquery'));
                wp_register_script( 'tsp_fullcalendar', plugins_url('../assets/fullcalendar-3.9.0/fullcalendar.min.js',__FILE__) , array('jquery'));
            }, 20);

        add_action( 'widgets_init', function () {
                register_widget( 'TravelSportsPro\GroupclassesWidget' );
                register_widget( 'TravelSportsPro\RegisterWidget' );
                register_widget( 'TravelSportsPro\CalendarWidget' );
            });

        add_shortcode('tsp_classes_list', function ( $atts ) {

            wp_enqueue_style('tsp_css_bootstrap');
            wp_enqueue_style('tsp_datetimepicker');
            wp_enqueue_style('tsp_fontawesome');
            wp_enqueue_style('tsp_style');
            wp_enqueue_style('tsp_style_united');
            wp_enqueue_style('tsp_fullcalendar');
            
            wp_dequeue_script( 'bootstrap' );
            
            wp_enqueue_script('tsp_js_bootstrap');
            wp_enqueue_script('tsp_momentjs');
            wp_enqueue_script('tsp_signature_pad');
            wp_enqueue_script('tspfunctionjs');
            wp_enqueue_script('tsp_datetimepicker');
            wp_enqueue_script('tsp_fullcalendar');

            $args = array(
                'before_widget' => '<div class="box widget">',
                'after_widget'  => '</div>',
                'before_title'  => '<div class="widget-title">',
                'after_title'   => '</div>',
            );

            ob_start();
            the_widget( 'TravelSportsPro\GroupclassesWidget', $atts, $args );
            $output = ob_get_clean();
            return   $output;
        });

        add_shortcode('tsp_calendar', function ( $atts ) {

            wp_enqueue_style('tsp_css_bootstrap');
            wp_enqueue_style('tsp_datetimepicker');
            wp_enqueue_style('tsp_fontawesome');
            wp_enqueue_style('tsp_style');
            wp_enqueue_style('tsp_style_united');
            wp_enqueue_style('tsp_fullcalendar');
            
            wp_dequeue_script( 'bootstrap' );

            wp_enqueue_script('tsp_js_bootstrap');
            wp_enqueue_script('tsp_momentjs');
            wp_enqueue_script('tsp_signature_pad');
            wp_enqueue_script('tspfunctionjs');
            wp_enqueue_script('tsp_datetimepicker');
            wp_enqueue_script('tsp_fullcalendar');

            $args = array(
                'before_widget' => '<div class="box widget">',
                'after_widget'  => '</div>',
                'before_title'  => '<div class="widget-title">',
                'after_title'   => '</div>',
            );

            ob_start();
            the_widget( 'TravelSportsPro\CalendarWidget', $atts, $args );
            $output = ob_get_clean();
            return   $output;
        });

        add_shortcode('tsp_register', function ( $atts ) {

            wp_enqueue_style('tsp_css_bootstrap');
            wp_enqueue_style('tsp_datetimepicker');
            wp_enqueue_style('tsp_fontawesome');
            wp_enqueue_style('tsp_style');
            wp_enqueue_style('tsp_style_united');
            wp_enqueue_style('tsp_fullcalendar');
            
            wp_dequeue_script( 'bootstrap' );

            wp_enqueue_script('tsp_js_bootstrap');
            wp_enqueue_script('tsp_momentjs');
            wp_enqueue_script('tsp_signature_pad');
            wp_enqueue_script('tspfunctionjs');
            wp_enqueue_script('tsp_datetimepicker');
            wp_enqueue_script('tsp_fullcalendar');

            $args = array(
                'before_widget' => '<div class="box widget">',
                'after_widget'  => '</div>',
                'before_title'  => '<div class="widget-title">',
                'after_title'   => '</div>',
            );

            ob_start();
            the_widget( 'TravelSportsPro\RegisterWidget', $atts, $args );
            $output = ob_get_clean();
            return   $output;
        });

        add_shortcode('tsp_client', function ( $atts ) {

            wp_enqueue_style('tsp_css_bootstrap');
            wp_enqueue_style('tsp_datetimepicker');
            wp_enqueue_style('tsp_fontawesome');
            wp_enqueue_style('tsp_style');
            wp_enqueue_style('tsp_style_united');
            wp_enqueue_style('tsp_fullcalendar');
            
            wp_dequeue_script( 'bootstrap' );

            wp_enqueue_script('tsp_js_bootstrap');
            wp_enqueue_script('tsp_momentjs');
            wp_enqueue_script('tsp_signature_pad');
            wp_enqueue_script('tspfunctionjs');
            wp_enqueue_script('tsp_datetimepicker');
            wp_enqueue_script('tsp_fullcalendar');

            unset($_SESSION['tsp_client_attrs']);
            foreach ($atts as $k_att => $att) {
                if ($k_att == 'class_genre')
                    $k_att = 'class_name';
                if (strpos ( $att , '|') !== false)
                    $_SESSION['tsp_client_attrs'][$k_att] = explode('|',$att);
                else
                    $_SESSION['tsp_client_attrs'][$k_att] = sanitize_text_field($att);
                    
                if (is_array($_SESSION['tsp_client_attrs'][$k_att]))
                    foreach($_SESSION['tsp_client_attrs'][$k_att] as $k => $v)
                        $_SESSION['tsp_client_attrs'][$k_att][$k] = sanitize_text_field($v);
            }

            ob_start();
            self::$client->Output();
            $output = ob_get_clean();
            return   $output;
        });
    }

    public static function GetApi()
    {
        return self::$api;
    }

    public static function GetClient()
    {
        return self::$client;
    }

    public static function GetTemplate()
    {
        return self::$template;
    }

    public static function GetEmailer()
    {
        return self::$emailer;
    }

    public static function GetError()
    {
        return self::$error;
    }
}