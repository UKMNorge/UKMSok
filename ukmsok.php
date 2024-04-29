<?php
/* 
Plugin Name: UKM Søk Arrangørsystemet
Plugin URI: http://www.ukm.no
Description: Søk funksjonalitet på arrangørsystemet
Author: UKM Norge / Kushtrim Aliu
Version: 1.0 
Author URI: http://www.ukm.no
*/

use UKMNorge\Wordpress\Modul;
use UKMNorge\Arrangement\Arrangement;



// ini_set('display_errors', true);
require_once('UKM/Autoloader.php');

class UKMSok extends Modul {
    public static $action = 'ukmsok';
    public static $path_plugin = null;


    public static function hook() {
        add_action(
            'wp_ajax_UKMsok_ajax',
            ['UKMSok', 'ajax']
        );

        add_action(
            'admin_print_styles',
            ['UKMSok', 'scripts_and_styles']
        );

        add_action(
            'network_admin_menu',
            ['UKMSok', 'addMenyItem'],
            2000
        );
    }

    public static function addMenyItem() {
        add_menu_page(
            'Admin søk arrangørsystemet',
            'Søk',
            'edit_posts',
            'UKMSok',
            ['UKMSok', 'renderAdminPage'],
            'dashicons-search',
            100
        );
    }

    public static function renderAdminPage() {
        ?>
            <div id="UKMSokAdmin"></div>
        <?php

        wp_enqueue_style('UKMSokAdminVueStyle', plugin_dir_url(__FILE__) . '/client/client_admin/dist/assets/build.css');
        wp_enqueue_script('UKMSokAdminVueJs', plugin_dir_url(__FILE__) . '/client/client_admin/dist/assets/build.js','','',true);
    }

    /**
     * Legg til menyelement
     *
     * @return void
     */
    public static function meny() {
        
    }

    

    public static function scripts_and_styles() {
        // Because of conflict
        // if( isset( $_GET['page'] ) && $_GET['page'] == 'UKMSok') {
        //     return;
        // }
        wp_enqueue_script('UKMSokVueJs', plugin_dir_url(__FILE__) . '/client/client_search/dist/build.js','','',true);
        // wp_enqueue_style('UKMSokVueStyle', plugin_dir_url(__FILE__) . '/client/client_search/dist/assets/build.css');
    }
}



UKMSok::init(__DIR__);
UKMSok::hook();
