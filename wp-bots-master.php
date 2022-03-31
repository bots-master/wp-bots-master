<?php
/**
 * @package           Bots Master - Development
 * @author            Pavlo M. <webxid@ukr.net>
 *
 * @wordpress-plugin
 *
 * Plugin Name:       Bots Master [Development Mode]
 * Plugin URI:        https://github.com/bots-master/wp-bots-master
 * GitHub Plugin URI: https://github.com/bots-master/wp-bots-master
 * Description:       The plugin helps to send a message to a Telegram Bot subscribers
 * Author:            Pavlo M. <webxid@ukr.net>
 * Author URI:        https://github.com/webxid
 * License:           Apache 2.0
 * Text Domain:       bots-master
 * Version:           1.0.0
 * Copyright:         © 2022 Pavlo M.
 */

define('WX_BOTS_MASTER_DEV_MODE', true);

require_once __DIR__ . '/bots-master/bots-master.php';
