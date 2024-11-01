<?php
/*class admin page*/
class Share_Computy_Admin {
    public function __construct()
    {

        $this->plugin_name = SHARE_COMPUTY;

    }
    public  static function init() {
        add_action( 'admin_menu', array( 'Share_Computy_Admin', 'add_admin_menu' ) );/* инициализируем меню в админке*/
        add_action( 'admin_enqueue_scripts', array( 'Share_Computy_Admin', 'load_scripts' ) );/*Загружаем скрипты и стили*/
        add_action( 'admin_init', array( 'Share_Computy_Admin', 'plugin_settings' ) );/*Вывод настроек в меню*/

      add_filter( 'plugin_action_links_share-computy/share-computy.php' , array( 'Share_Computy_Admin', 'share_plugin_settings_link' ) ); /*добавляем ссылку на настройки на странице плагинов */
    }


    public static function plugin_settings() {
        /*Вывод настроек в меню*/
register_setting( 'option_group_share', 'share_computy_option', 'sanitize_callback' );
$trans1 = __( 'Plugin settings', 'share-computy' );
$trans_soc = __( 'Select icons to display', 'share-computy' );
$trans2 = __( 'Title before buttons', 'share-computy' );
 add_settings_section( 'share_section_id', $trans1, '', 'primer_page_share' );
  add_settings_field( 'bonus_field4', $trans2, array( 'Share_Computy_Admin', 'title_share' ), 'primer_page_share', 'share_section_id' );

  add_settings_field('social_icons', $trans_soc, array('Share_Computy_Admin', 'fill_share'), 'primer_page_share', 'share_section_id');

    }


    public static function share_plugin_settings_link( $links ) {
        /*добавляем ссылку на настройки на странице плагинов */
        $settings_link = '<a href="admin.php?page=share_computy_options">'.__( 'Settings and results', 'share-computy' ).'</a>';
        $links[] = $settings_link;
        return $links;
    }

    public static function add_admin_menu() {
        /* инициализируем меню в админке*/
        $menu_title =  __('Share computy', 'share-computy');
        add_menu_page( $menu_title, $menu_title, 'edit_others_posts', 'share_computy_options', array( 'Share_Computy_Admin', 'share_computy_options'  ), 'dashicons-thumbs-up', 20 );

            }


    public static function load_scripts() {
        /*Загружаем скрипты и стили*/
        wp_register_style( 'share-computy-style-admin', plugin_dir_url( __FILE__ ) . '/share-computy-style-admin.css', array(), SHARE_COMPUTY_VERSION );
        wp_enqueue_style( 'share-computy-style-admin' );
    }


    public static function share_computy_options(){

        /*Страница меню*/
        if( current_user_can('manage_options') ){?>

            <div class="wrap share-computy-admin">

            <h2><?php echo _e( 'Share computy', 'share-computy' ), ' <small>v', SHARE_COMPUTY_VERSION.'</small>'; ?></h2>
            <p><?php echo __( 'With the support of', 'share-computy' );?> <a href="https://computy.ru" target="_blank" title="Разработка и поддержка сайтов на WordPress"> computy </a> <br>
                <a href="https://yoomoney.ru/to/410011302808683" target="_blank"><?php echo __( 'Throw money at me!', 'share-computy' );?></a><br>
                <a href="https://computy.ru/blog/plugin-share-computy" target="_blank"><?php echo __( 'About plugin', 'share-computy' );?></a>
            </p>
            <hr>
            <h2><?php echo _e( 'Plugin description', 'share-computy' ); ?></h2>
            <p><?php echo __( 'This plugin adds a system of social media icons to your site to share an article.', 'share-computy' ); ?></p>
            <p><img src="<?php echo SHARE_COMPUTY_PLUGIN_URL;?>admin/img/1.jpg" alt=""></p>
            <p><?php echo __( 'Both registered and unregistered users can share on social networks.<br>The plugin is protected from cheat votes with a time interval of 15 seconds', 'share-computy' ); ?></p>
            <p><?php echo __( 'For the plugin to work, you need to insert the <b> [buttons_share_computy] </b> shortcode in the article editor. <br> If you want to insert the shortcode into the php files of your theme, insert this code:', 'share-computy' ); ?><b> &#60;?php echo do_shortcode( '[buttons_share_computy]' ); ?&#62;</b></p>
            <p><?php echo __( 'To display a list of the most popular articles, use the <b>[popular_share_computy count="5"]</b> shortcode, where count is the number of posts displayed in descending order. Popularity is calculated by the total number of share received.', 'share-computy' ); ?></p>
            <p><?php echo __( 'To display the total number of reposts for a specific post, use the shortcode: <b>[total_post_share_computy]</b>.', 'share-computy' ); ?></p>



 <form action="options.php" method="POST">
                        <?php
                        settings_fields('option_group_share');
                        do_settings_sections('primer_page_share');
                        submit_button();
                        ?>
                        </form>
        <?php }
    }


 public static function title_share() {
       $val = get_option( 'share_computy_option' );
        $value = $val['title-share'] ?? __( 'Share', 'bonus-for-woo' );
        ?>
        <input style="width: 350px" type="text" name="share_computy_option[title-share]" value="<?php echo esc_attr( $value ) ?>" />
        <?
 }
     public static function fill_share() {
        $val = get_option( 'share_computy_option' );
        $checkedvk = isset($val['share-vk']) ? "checked" : "";
        $checkedfb = isset($val['share-fb']) ? "checked" : "";
        $checkedok = isset($val['share-ok']) ? "checked" : "";
        $checkedtw = isset($val['share-tw']) ? "checked" : "";
        $checkedtg = isset($val['share-tg']) ? "checked" : "";
        $checkedwhatsapp = isset($val['share-whatsapp']) ? "checked" : "";
        $checkedviber = isset($val['share-viber']) ? "checked" : "";
        ?>
        <input id="share-social-vk" name="share_computy_option[share-vk]" type="checkbox" value="1" <?php echo $checkedvk; ?>>
        <label for="share-social-vk" >VK</label>
<br>
        <input id="share-social-fb" name="share_computy_option[share-fb]" type="checkbox" value="1" <?php echo $checkedfb; ?>>
        <label for="share-social-fb">FACEBOOK</label>
<br>
        <input id="share-social-ok" name="share_computy_option[share-ok]" type="checkbox" value="1" <?php echo $checkedok; ?>>
        <label for="share-social-ok">ODNOKLASSNIKI</label>
        <br>
        <input id="share-social-tw" name="share_computy_option[share-tw]" type="checkbox" value="1" <?php echo $checkedtw; ?>>
        <label for="share-social-tw">TWITTER</label>
<br>
        <input id="share-social-tg" name="share_computy_option[share-tg]" type="checkbox" value="1" <?php echo $checkedtg; ?>>
        <label for="share-social-tg" >TELEGRAM</label>
<br>
        <input id="share-social-whatsapp" name="share_computy_option[share-whatsapp]" type="checkbox" value="1" <?php echo $checkedwhatsapp; ?>>
        <label for="share-social-whatsapp">WHATSAPP</label>
<br>
        <input id="share-social-viber" name="share_computy_option[share-viber]" type="checkbox" value="1" <?php echo $checkedviber; ?>>
        <label for="share-social-viber" >VIBER</label>
    <?php }





}