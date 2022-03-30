<?php
/**
 * Plugin Name:       WP Telegram Bot Master
 * Plugin URI:        https://github.com/bots-master/wp-bot-master
 * Description:       This plugin contain core logic. Do not remove it
 * Author:            Pavlo M. <webxid@ukr.net>
 * Author URI:        https://github.com/webxid
 * License:           Apache 2.0
 * Text Domain:       wp-bot-master
 * Version:           1.0.0
 * Copyright:         © 2022 Pavlo M.
 *
 * @package  WP Telegram Bot Master
 * @author   Pavlo M. <webxid@ukr.net>
 */

namespace WebXID\BotMaster;

//ini_set('display_errors', 1);
//ini_set('display_startup_errors', 1);
//error_reporting(E_ALL);

require_once __DIR__ . '/bootstrap.php';

BotMaster::make()
    ->init()
    ->initAdmin()
    ->initRestAPI();

