<?php

namespace WebXID\BotsMaster;

use WebXID\BotsMaster\Admin\Controllers\ChannelsListController;
use WebXID\BotsMaster\Admin\Controllers\SendMessageController;
use WebXID\BotsMaster\Admin\Controllers\SettingsController;
use WebXID\BotsMaster\Admin\MenuRegistrer;
use WebXID\BotsMaster\ChatBot\BotUser;
use WebXID\BotsMaster\Controllers\WebHookController;
use WebXID\EDMo\AbstractClass\BasicDataContainer;
use WebXID\EDMo\DB;

class BotsMaster extends BasicDataContainer
{
    #region Actions

    public function init()
    {
        Config::init();

        $default_config = [
            DB::DEFAULT_CONNECTION_NAME => [
                'host' => DB_HOST,
                'port' => '3306',
                'user' => DB_USER,
                'pass' => DB_PASSWORD,
                'db_name' => DB_NAME,
                'use_persistent_connection' => false,
                'charset' => DB_CHARSET,
            ],
        ];

        DB::addConfig($default_config);

        return $this;
    }

    /**
     *
     */
    public function initAdmin()
    {
        if (!is_admin()) {
            return $this;
        }

        // Installation logic
        register_activation_hook( Tpl::route('bots-master.php'), [$this, 'pluginActivation'] );
        register_deactivation_hook( Tpl::route('bots-master.php'), [$this, 'pluginDeactivation'] );

        // Init Admin Menu
        add_action( 'admin_menu', [$this, 'initAdmisnMenu'] );

        return $this;
    }

    public function initRestAPI()
    {
        add_action( 'rest_api_init', function () {
            register_rest_route( 'wx-bots-master', 'webhook/telegram.json', [
                'methods' => \WP_REST_Server::ALLMETHODS,
                'callback' => [WebHookController::make(), 'telegram'],
            ]);
        } );
    }

    #endregion

    #region Sub Actions

    /**
     *
     */
    public function initAdmisnMenu()
    {
        $parent_menu_slug = 'bots-master';

        if (!wx_config(Config::TELEGRAM_API_TOKEN))  {
            MenuRegistrer::make()
                ->menuTitle(__( 'Bot Master', 'bots_master' ))
                ->subMenuTitle('⚙️ ' .  __( 'Bot Settings', 'bots_master' ))
                ->pageTitle('⚙️ ' .  __( 'Bot Master Settings', 'bots_master' ))
                ->slug($parent_menu_slug)
                ->requestHendler(SettingsController::class)
                ->capability('manage_options')
                ->iconUrl(plugins_url('bots-master/assets/images/icon.svg'))
                ->register();

            MenuRegistrer::childTo($parent_menu_slug)
                ->menuTitle('📢 ' . __( 'Channels', 'bots_master' ))
                ->pageTitle('📢 ' . __( 'Channels', 'bots_master' ))
                ->slug('bots-master-channels-list')
                ->requestHendler(ChannelsListController::class)
                ->capability('manage_options')
                ->register();

            MenuRegistrer::childTo($parent_menu_slug)
                ->menuTitle('🚀 ' . __( 'Send Message', 'bots_master' ))
                ->pageTitle('🚀 ' . __( 'Send message', 'bots_master' ))
                ->slug('bots-master-send-message')
                ->requestHendler(SendMessageController::class)
                ->capability('manage_options')
                ->register();
        } else {
            $parent_menu_slug = 'bots-master-send-message';

            MenuRegistrer::make()
                ->menuTitle(__( 'Bot Master', 'bots_master' ))
                    ->subMenuTitle('🚀️ ' . __( 'Send Message', 'bots_master' ))
                    ->pageTitle('🚀 ' . __( 'Send message', 'bots_master' ))
                ->capability('manage_options')
                ->slug($parent_menu_slug)
                ->iconUrl(plugins_url('bots-master/assets/images/icon.svg'))
                ->requestHendler(SendMessageController::class)
                ->register();

            MenuRegistrer::childTo($parent_menu_slug)
                ->menuTitle('📢 ' . __( 'Channels', 'bots_master' ))
                ->pageTitle('📢 ' . __( 'Channels', 'bots_master' ))
                ->slug('bots-master-channels-list')
                ->requestHendler(ChannelsListController::class)
                ->capability('manage_options')
                ->register();

            MenuRegistrer::childTo($parent_menu_slug)
                ->menuTitle('⚙️ ' . __( 'Bot Settings', 'bots_master' ))
                ->pageTitle('⚙️ ' .  __( 'Bot Master Settings', 'bots_master' ))
                ->capability('manage_options')
                ->slug('bots-master')
                ->requestHendler(SettingsController::class)
                ->register();
        }
    }

    /**
     *
     */
    public function pluginActivation()
    {
        Config::set(Config::WELCOME_MESSAGE, 'WELCOME_MESSAGE');
        Config::set(Config::UNKNOWN_MESSAGE, 'UNKNOWN_MESSAGE');

        // Install DB
        DB::query("
            CREATE TABLE `" . BotUser::TABLE_NAME . "` (
                    `provider_id` INT(10) NOT NULL,
                    `provider_user_id` VARCHAR(256) NOT NULL COLLATE 'utf8mb4_unicode_ci',
                    `type_id` TINYINT(3) NOT NULL DEFAULT '1' COMMENT 'User, by default',
                    `first_name` VARCHAR(256) NOT NULL DEFAULT '' COLLATE 'utf8mb4_unicode_ci',
                    `last_name` VARCHAR(256) NOT NULL DEFAULT '' COLLATE 'utf8mb4_unicode_ci',
                    `username` VARCHAR(256) NOT NULL DEFAULT '' COLLATE 'utf8mb4_unicode_ci',
                    `locale` VARCHAR(5) NOT NULL DEFAULT 'en' COLLATE 'utf8mb4_unicode_ci',
                    `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    PRIMARY KEY (`provider_id`, `provider_user_id`) USING BTREE,
                    INDEX `username` (`username`) USING BTREE,
                    INDEX `updated_at` (`updated_at`) USING BTREE
                )
                COLLATE='utf8mb4_unicode_ci'
                ENGINE=InnoDB
                ;

        ")
        ->execute();
    }

    /**
     *
     */
    public function pluginDeactivation()
    {
        Config::delete(Config::TELEGRAM_API_TOKEN);
        Config::delete(Config::WELCOME_MESSAGE);
        Config::delete(Config::UNKNOWN_MESSAGE);

        // CleanUp DB
        DB::query('DROP TABLE IF EXISTS `' . BotUser::TABLE_NAME . '`;')
            ->execute();
    }

    #endregion
}
