<?php
/**
 * Plugin Name: Backend Linker
 * Description: A WordPress Plugin to automatically link parts of the content based on certain rules.
 * Version: 1.0.0
 * Author: Thomas Maier (webzunft), ChatGPT
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

defined( 'ABSPATH' ) or die();

require_once plugin_dir_path( __FILE__ ) . 'classes/class-backend-linker.php';
require_once plugin_dir_path( __FILE__ ) . 'classes/class-backend-linker-admin.php';

$plugin = new Webzunft_Backend_Linker();
new Webzunft_Backend_Linker_Admin();

$plugin->run();