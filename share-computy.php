<?php
/*
* Plugin Name:   Share computy
* Version:       1.2.4
* Text Domain:   share-computy
* Plugin URI:    https://computy.ru/blog/plugin-share-computy
* Description:    Displaying share buttons for an article, page or product card using the [buttons_share_computy] shortcode.
* Author:        computy
* Author URI:    https://computy.ru
*/
if ( !defined( 'ABSPATH' ) ) exit;
define( 'SHARE_COMPUTY_PLUGIN_DIR', plugin_dir_path( __FILE__ ) );

if ( ! defined( 'SHARE_COMPUTY_PLUGIN_URL' ) ) {
    define( 'SHARE_COMPUTY_PLUGIN_URL', plugins_url( '/', __FILE__ ) );
}
define( 'SHARE_COMPUTY_VERSION', '1.2.4' );

/*Страница админки*/
if ( is_admin() || ( defined( 'WP_CLI' ) && WP_CLI ) ) {
    require_once( SHARE_COMPUTY_PLUGIN_DIR . '/admin/settings.php' );
    add_action( 'init', array( 'Share_Computy_Admin', 'init' ) );
}
/*Страница админки*/



/*Функция, которая запускается при активации плагина*/
register_activation_hook( __FILE__, 'share_computy_activate' );
function share_computy_activate() {
    global $wpdb;
    $table_name = $wpdb->prefix . 'share_computy';

    if($wpdb->get_var("SHOW TABLES share '$table_name'") != $table_name) {
        $charset_collate = $wpdb->get_charset_collate();
        $sql = "CREATE TABLE $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		post_id mediumint(10) NOT NULL,
		session_id varchar(60) DEFAULT '' NOT NULL,
		vote varchar(10) NOT NULL,
		date_vote datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,
		PRIMARY KEY  (id)
	) $charset_collate;";

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        dbDelta($sql);
    }
}

//работа с сессиями
function share_computy_session_start()
{
    $sn = session_name();
    if (isset($_COOKIE[$sn])) {
        $sessid = $_COOKIE[$sn];
    } elseif (isset($_GET[$sn])) {
        $sessid = $_GET[$sn];
    } else {
        return session_start(['read_and_close' => true]);
    }

    if (!preg_match('/^[a-zA-Z0-9,\-]{22,40}$/', $sessid)) {
        return false;
    }
    return session_start(['read_and_close' => true]);
}

add_action('mu_plugin_loaded', 'shareStartSession', 1);

function shareStartSession() {
    if (!isset($_SESSION)) {

        share_computy_session_start();
    }

}


/*Обработка ajax*/
function share_js_variables(){
    $variables = array (
        'ajax_url' => admin_url('admin-ajax.php'),
        'is_mobile' => wp_is_mobile()
    );
    echo(
    '<script type="text/javascript">window.wp_data = '.json_encode($variables). ';</script>'
    );
}
add_action('wp_head','share_js_variables');

if( wp_doing_ajax() ) {
    add_action('wp_ajax_get_share_computy_value', 'get_share_computy_value_callback');
    add_action('wp_ajax_nopriv_get_share_computy_value', 'get_share_computy_value_callback');
}
function get_share_computy_value_callback(){
    //тут обработка первого аякс запроса
$sesid = sanitize_text_field($_POST['sesid']);
$postid = sanitize_text_field($_POST['postid']);
$voteid = sanitize_text_field($_POST['voteid']);

//проверяем есть ли голос у этого sesid в этой postid
    // подготавливаем данные
    global $wpdb;
    $table_name = $wpdb->prefix . 'share_computy';

       //голоса у этого sesid нет, значит можно добавлять
        $wpdb->insert( $table_name, array(
            'post_id' => $postid,
            'session_id' => $sesid,
            'vote' => $voteid,
            'date_vote' => date("Y-m-d H:i:s"),
        ), array("%s", "%s", "%s", "%s")  );
        echo 'voteadd';

}
/*Обработка ajax*/



/*добавляем стили на фронте*/
function share_computy_styles() {
    wp_register_style( 'share-computy-style', plugin_dir_url( __FILE__ ) . 'view/share-computy-style.css' );
    wp_enqueue_style( 'share-computy-style' );
}
add_action( 'get_footer', 'share_computy_styles' );




/*добавляем скрипты на фронте*/
function share_computy_script() {
    wp_register_script( 'share-computy-script', plugin_dir_url( __FILE__ ) . 'view/share-computy-script.js', array( 'jquery' ), null, true );
    wp_enqueue_script( 'share-computy-script' );
}
add_action( 'wp_enqueue_scripts', 'share_computy_script' );



/*функция вывода share кнопок*/
function get_share_computy_buttons_template(){
    require_once (SHARE_COMPUTY_PLUGIN_DIR.'view/template_buttons.php');
    return share_computy_buttons();
}



/*вывод share кнопок с помощью шорт кода*/
add_shortcode( 'buttons_share_computy', 'share_computy_shortcode' );
function share_computy_shortcode(){
    $userid = get_current_user_id();//  id пользователя
    return get_share_computy_buttons_template();
}
/*вывод share кнопок с помощью шорткода*/


/*Вывод общего количество репостов для записи*/
add_shortcode( 'total_post_share_computy', 'total_post_share_computy_shortcode' );
function total_post_share_computy_shortcode(){
    $post_id = get_the_ID();
    global $wpdb;
    $table_name = $wpdb->prefix . 'share_computy';
    $count1 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'vk'
    ) );
    $count2 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'fb'
    ) );
    $count3 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'ok'
    ) );
    $count4 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'tw'
    ) );
    $count5 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'tg'
    ) );
    $count6 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'whatsapp'
    ) );
    $count7 = $wpdb->get_var( $wpdb->prepare(
        "SELECT COUNT(*) FROM " . $table_name. " WHERE  post_id = %d AND vote= %s",
        $post_id,'viber'
    ) );

    return $count1+$count2+$count3+$count4+$count5+$count6+$count7;

}
/*Вывод общего количество репостов для записи*/


/*вывод самых популярных */
add_shortcode( 'popular_share_computy', 'popular_share_computy_shortcode' );
function popular_share_computy_shortcode($atts){
    $atts = shortcode_atts( [
        'count' => 5,
    ], $atts );
    $limit = $atts['count'];
    global $wpdb;
    $table_name = $wpdb->prefix . 'share_computy';

     $table_lc = $wpdb->get_results( "SELECT post_id, COUNT(vote) as vote FROM " . $table_name. " GROUP BY post_id ORDER BY vote DESC LIMIT $limit " );

   $table_lc = json_decode(json_encode($table_lc), true);

    $students = [];
    $k='';
    foreach ($table_lc as $value=>$item){
        $students[$k] = $item['post_id'];
        $k++;
    }


    $query = new WP_Query( [
        'post__in'  => $students,
        'orderby' => 'post__in'
    ] );
    $return = '<ul>';
    while  ($query->have_posts() ) : $query->the_post();
        $return .= '<li class="item-share-computy"><a href="'.get_the_permalink().'" title="'.get_the_title().'">'.get_the_title().'</a></li>';
     endwhile; wp_reset_postdata();
    $return.= '</ul>';
    return $return;
}