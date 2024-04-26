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
    }

    /**
     * Legg til menyelement
     *
     * @return void
     */
    public static function meny() {

    }

    public static function scripts_and_styles() {
        wp_enqueue_style('UKMSokVueStyle', plugin_dir_url(__FILE__) . '/client/dist/assets/build.css');
        wp_enqueue_script('UKMSokVueJs', plugin_dir_url(__FILE__) . '/client/dist/assets/build.js','','',true);
    }
}

UKMSok::init(__DIR__);
UKMSok::hook();
