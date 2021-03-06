<?php

defined( 'ABSPATH' ) || exit;

add_action('wpmu_new_blog', 'sideka_site_init', 10, 2);

function sideka_site_init_pages($user_id){
    $pages = array();

    $pages["home"] = wp_insert_post(array(
        'post_title'     => 'Beranda',
        'post_status'    => 'publish',
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));
    update_post_meta( $pages["home"], '_wp_page_template', 'homepage.php' );

    $pages["profile"] = wp_insert_post(array(
        'post_name'     => 'profil',
        'post_title'     => 'Profil Desa',
        'post_status'    => 'publish',
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));

    $pages["history"] = wp_insert_post(array(
        'post_name'     => 'sejarah',
        'post_title'     => 'Sejarah Desa',
        'post_status'    => 'publish',
        'post_parent'    => $pages["profile"],
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));

    $pages["lembaga"] = wp_insert_post(array(
        'post_name'     => 'lembaga',
        'post_title'     => 'Lembaga Desa',
        'post_status'    => 'publish',
        'post_parent'    => $pages["profile"],
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));

    $pages["data"] = wp_insert_post(array(
        'post_name'     => 'data',
        'post_title'     => 'Data Desa',
        'post_status'    => 'publish',
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));

    $pages["kependudukan"] = wp_insert_post(array(
        'post_name'     => 'kependudukan',
        'post_title'     => 'Data Kependudukan',
        'post_status'    => 'publish',
        'post_parent'    => $pages["data"],
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));
    update_post_meta( $pages["kependudukan"], '_wp_page_template', 'template-full.php' );

    $pages["anggaran"] = wp_insert_post(array(
        'post_name'     => 'anggaran',
        'post_title'     => 'Anggaran Desa',
        'post_status'    => 'publish',
        'post_parent'    => $pages["data"],
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));
    update_post_meta( $pages["anggaran"], '_wp_page_template', 'template-full.php' );

    $pages["geospasial"] = wp_insert_post(array(
        'post_name'     => 'geospasial',
        'post_title'     => 'Peta Desa',
        'post_status'    => 'publish',
        'post_parent'    => $pages["data"],
        'post_author'    => $user_id,
        'post_type'      => 'page',
    ));
    update_post_meta( $pages["geospasial"], '_wp_page_template', 'template-full.php' );

    $defaultPage = get_page_by_title( 'Laman Contoh' );
    wp_delete_post( $defaultPage->ID );

    update_option('sideka_page_ids', array(
        'kependudukan' => $pages['kependudukan'],
        'anggaran' => $pages['anggaran'],
        'geospasial' => $pages['geospasial'],
    ));
    return $pages;
}

function sideka_get_category_configs(){
    $configs = array();
    $configs['news']  =array('cat_name' => 'Kabar Desa',  'category_nicename' => 'kabar');
    $configs['product']  =array('cat_name' => 'Produk Desa',  'category_nicename' => 'produk');
    $configs['potential']  =array('cat_name' => 'Potensi Desa',  'category_nicename' => 'potensi');
    $configs['government']  =array('cat_name' => 'Pemerintahan',  'category_nicename' => 'pemerintahan');
    $configs['dana-desa']  =array('cat_name' => 'Penggunaan Dana Desa',  'category_nicename' => 'dana-desa');
    $configs['seni-kebudayaan']  =array('cat_name' => 'Seni dan Kebudayaan',  'category_nicename' => 'seni-kebudayaan');
    $configs['tokoh']  = array('cat_name' => 'Tokoh Masyarakat',  'category_nicename' => 'tokoh');
    $configs['lingkungan']  = array('cat_name' => 'Lingkungan',  'category_nicename' => 'lingkungan');
    return $configs;
}

function sideka_get_event_category_configs(){
    $configs = array();
    $configs[]  =array('cat_name' => 'Keagamaan',  'category_nicename' => 'keagamaan');
    $configs[]  =array('cat_name' => 'Musyawarah Desa',  'category_nicename' => 'musyawarah-desa');
    $configs[]  =array('cat_name' => 'Olahraga',  'category_nicename' => 'olahraga');
    $configs[]  =array('cat_name' => 'Pendidikan',  'category_nicename' => 'pendidikan');
    $configs[]  = array('cat_name' => 'Seni dan Kebudayaan',  'category_nicename' => 'seni-kebudayaan');
    $configs[]  = array('cat_name' => 'Syukuran',  'category_nicename' => 'syukuran');
    foreach ($configs as $key => $config){
        $configs[$key]["taxonomy"] = "tribe_events_cat";
    }
    return $configs;
}

function sideka_site_init_categories() {
    $categories = array();

    $configs = sideka_get_category_configs();
    foreach ($configs as $key => $config){
        $categories[$key] = wp_insert_category($config);
    }

    $event_configs = sideka_get_event_category_configs();
    foreach ($event_configs as $event_config){
        wp_insert_category($event_config);
    }

    return $categories;
}

function sideka_get_role_configs(){
    $configs = array();
    $configs[] = array("penduduk", "Admin Kependudukan", array('edit_penduduk'=>true));
    $configs[] = array("keuangan", "Admin Keuangan", array('edit_keuangan'=>true));
    $configs[] = array("pemetaan", "Admin Pemetaan", array('edit_pemetaan'=>true));
    $configs[] = array("posyandu", "Admin Posyandu", array('edit_posyandu'=>true));
    return $configs;
}

function sideka_site_init_roles() {
    $configs = sideka_get_role_configs();
    foreach ($configs as $config){
        add_role($config[0], $config[1], $config[2]);
    }
}

function sideka_get_nav_menu_configs(){
    $configs = [];
    $configs[] = array('menu-item-title' => "Kegiatan",
        'menu-item-type' => 'post_type_archive',
        'menu-item-object' => "tribe_events",
        'menu-item-url' => get_post_type_archive_link("tribe_events"),
        'menu-item-status' => "publish"
    );
    return $configs;
}


function sideka_site_init_menu($pages, $categories) {
    $menu = array();
    $menu_id = wp_create_nav_menu("Menu Utama");

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Beranda',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["home"],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));

    $nav_profile =wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Profil Desa',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["profile"],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Sejarah Desa',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["history"],
        'menu-item-type' => 'post_type',
        'menu-item-parent-id' => $nav_profile,
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Lembaga Desa',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["lembaga"],
        'menu-item-type' => 'post_type',
        'menu-item-parent-id' => $nav_profile,
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
            'menu-item-title' => 'Kabar Desa',
            'menu-item-object-id' => $categories['news'],
            'menu-item-object' => 'category',
            'menu-item-type' => 'taxonomy',
            'menu-item-url' => get_category_link($categories['news']),
            'menu-item-status' => 'publish',));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Produk',
        'menu-item-object-id' => $categories['product'],
        'menu-item-object' => 'category',
        'menu-item-type' => 'taxonomy',
        'menu-item-url' => get_category_link($categories['product']),
        'menu-item-status' => 'publish',));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Potensi',
        'menu-item-object-id' => $categories['potential'],
        'menu-item-object' => 'category',
        'menu-item-type' => 'taxonomy',
        'menu-item-url' => get_category_link($categories['potential']),
        'menu-item-status' => 'publish',));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Kependudukan',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["kependudukan"],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));

    $nav_menu_configs = sideka_get_nav_menu_configs();
    foreach($nav_menu_configs as $config){
        wp_update_nav_menu_item($menu_id, 0, $config);
    }

    /*
    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Peta',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["geospasial"],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' => 'Anggaran',
        'menu-item-object' => 'page',
        'menu-item-object-id' => $pages["anggaran"],
        'menu-item-type' => 'post_type',
        'menu-item-status' => 'publish'));
    */

    $locations = array();
    $locations["main_nav"] = $menu_id; //main MajalahDesa nav
    set_theme_mod('nav_menu_locations', $locations);
}

function sideka_get_widget_configs(){
    $configs = [];
    $configs[] = array(
        'widget_tribe-events-list-widget',
        array (
          1 => 
          array (
            'title' => 'Kegiatan Mendatang',
            'limit' => '5',
            'no_upcoming_events' => '1',
            'featured_events_only' => false,
            'jsonld_enable' => 1,
          ),
          2 => 
          array (
            'title' => 'Kegiatan Mendatang',
            'limit' => '5',
            'no_upcoming_events' => '1',
            'featured_events_only' => false,
            'jsonld_enable' => 1,
          ),
          '_multiwidget' => 1,
        ),
        array(
            "tribe-events-list-widget-1" => array('sidebar', 'unshift'),
            "tribe-events-list-widget-2" => array('home-6', 'unshift')
        )
    );
    return $configs;
}

function sideka_apply_widget_configs($configs){
    $results = [];
    $widgets = get_option("sidebars_widgets");
    foreach($configs as $config){
        update_option($config[0], $config[1]);
        foreach($config[2] as $instance => $instance_config){
            $place = $instance_config[0];
            $method = $instance_config[1];
            if(!isset($widgets[$place])){
                $widgets[$place] = array();
            }
            if(in_array($instance, $widgets[$place])){
                continue;
            }
            if($method == "unshift"){
                array_unshift($widgets[$place], $instance);
            } else {
                array_push($widgets[$place], $instance);
            }
            $results[] = $instance;
        }
    }
    update_option('sidebars_widgets', $widgets);
    return $results;
}

function sideka_site_init_widgets($pages, $categories)
{
    update_option('widget_search', array(
        1 => array(
            'title' => '',
        ),
        2 => array(
            'title' => '',
        ),
        '_multiwidget' => 1
    ));
    update_option('widget_recent-comments', array(
        1 => array(
            'title' => '',
            'number' => 5,
        ),
        2 => array(
            'title' => '',
            'number' => 5,
        ),
        '_multiwidget' => 1
    ));
    update_option('widget_mh_magazine_lite_posts_large', array(
        1 => array(
            'category' => $categories["news"],
            'postcount' => 4,
        ),
        '_multiwidget' => 1
    ));
    update_option('widget_mh_magazine_lite_posts_stacked', array(
        1 => array(
            'title' => "Produk Desa",
            'category' => $categories["product"],
        ),
        2 => array(
            'title' => "Potensi Desa",
            'category' => $categories["potential"],
        ),
        '_multiwidget' => 1
    ));
    $widgets = array(
        'sidebar' => array(
            'search-1',
            'recent-comments-1'
        ),
        'home-2' => array(
            'mh_magazine_lite_posts_large-1'
        ),
        'home-6' => array(
            'search-2',
            'mh_magazine_lite_posts_stacked-1',
            'mh_magazine_lite_posts_stacked-2',
            'recent-comments-2'
        ),
    );
    update_option('sidebars_widgets', $widgets);

    $widget_configs = sideka_get_widget_configs();
    sideka_apply_widget_configs($widget_configs);

    //Update halo dunia! post categories
    $args = array(
        'name'        => 'halo-dunia',
        'post_type'   => 'post',
        'post_status' => 'publish',
        'numberposts' => 1
    );
    $posts = get_posts($args);
    wp_set_post_categories( $posts[0]->ID, array( $categories["news"], $categories["product"], $categories["potential"] ) );
}

function sideka_get_sitewide_option_names(){
    $results = array("category_base", "date_format", "jetpack_active_modules", "sharing-options", "sharing-services", "tag_base",
    "time_format", "tribe_events_calendar_options", "wp_user_roles", "akismet_comment_form_privacy_notice");
    return $results;
}

function sideka_get_initial_option_names(){
    $results = array("rewrite_rules");
    return $results;
}

function sideka_get_sitewide_options($site_id, $initial_options = false){
    switch_to_blog($site_id);

    $results = array();
    $option_names = sideka_get_sitewide_option_names();
    if($initial_options){
    	$option_names = array_merge($option_names, sideka_get_sitewide_option_names());
    }
    foreach($option_names as $option_name){
        $results[$option_name] = get_option($option_name);
    }
    restore_current_blog();

    return $results;
}


function sideka_site_init_theme($pages) {
    $upload = wp_upload_bits( "default_bg.jpg", null, file_get_contents(dirname(__FILE__)."/default_bg.jpg") );
    set_theme_mod('background_image', $upload["url"]);
    set_theme_mod('background_repeat', 'no-repeat');
    set_theme_mod('background_position_x', 'center');
    set_theme_mod('background_attachment', 'fixed');

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $pages['home'] );
}

function sideka_site_init($blog_id, $user_id){
    global $wpdb;

    switch_to_blog($blog_id);

    $domain =$wpdb->get_var( "select domain from wp_blogs where blog_id = '$blog_id.'");
    $wpdb->insert('sd_desa', array(
        'blog_id' => $blog_id,
        'domain' => $domain,
        'kode' => $_REQUEST["region3"],
    ));

    $pages = sideka_site_init_pages($user_id);
    $categories = sideka_site_init_categories();
    sideka_site_init_menu($pages, $categories);
    sideka_site_init_theme($pages);
    sideka_site_init_widgets($pages, $categories);
    sideka_site_init_roles();

    $master_options = sideka_get_sitewide_options(1, true);
    foreach($master_options as $option_name => $option_value){
	if ($option_name == "wp_user_roles"){
		$option_name = "wp_".$blog_id."_user_roles";
	}
        update_option($option_name, $option_value);
    }

    $master_options = sideka_get_sitewide_options(1);
    foreach($master_options as $option_name => $option_value){
	if ($option_name == "wp_user_roles"){
		$option_name = "wp_".$blog_id."_user_roles";
	}
        update_option($option_name, $option_value);
    }

    update_option( 'default_category', $categories['news'] );

    restore_current_blog();
}
