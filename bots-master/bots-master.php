<?php
/**
 * @since             1.0.0
 * @package           Bots Master
 * @author            Pavlo M. <webxid@ukr.net>
 *
 * @wordpress-plugin
 *
 * Plugin Name:       Bots Master
 * Plugin URI:        https://github.com/bots-master/wp-bots-master
 * GitHub Plugin URI: https://github.com/bots-master/wp-bots-master
 * Description:       The plugin helps to send a message to a Telegram Bot subscribers
 * Author:            Pavlo M. <webxid@ukr.net>
 * Author URI:        https://github.com/webxid
 * License:           Apache 2.0
 * Text Domain:       bots-master
 * Version:           1.0.0
 * Copyright:         © 2022 Pavlo M.
 *
 */

namespace WebXID\BotsMaster;

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

if (!defined('WX_BOTS_MASTER_DEV_MODE')) {
    define('WX_BOTS_MASTER_DEV_MODE', false);
}

require_once __DIR__ . '/bootstrap.php';

BotsMaster::make()
    ->init()
    ->initAdmin()
    ->initRestAPI();

