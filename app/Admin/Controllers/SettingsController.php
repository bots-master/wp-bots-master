<?php

namespace WebXID\BotMaster\Admin\Controllers;

use WebXID\BotMaster\ChatBot;
use WebXID\BotMaster\Config;

class SettingsController extends BasicController
{
    /**
     *
     */
    public function loadPage()
    {
        $this->view('admin/settings', [
            Config::TELEGRAM_API_TOKEN => Config::get( Config::TELEGRAM_API_TOKEN, ''),
            Config::WELCOME_MESSAGE => Config::get( Config::WELCOME_MESSAGE, ''),
            Config::UNKNOWN_MESSAGE => Config::get( Config::UNKNOWN_MESSAGE, ''),
        ]);
    }

    /**
     *
     */
    public function postRequest()
    {
        Config::set(Config::TELEGRAM_API_TOKEN, $_POST[Config::TELEGRAM_API_TOKEN]);
        Config::set(Config::WELCOME_MESSAGE, $_POST[Config::WELCOME_MESSAGE]);
        Config::set(Config::UNKNOWN_MESSAGE, $_POST[Config::UNKNOWN_MESSAGE]);

        $webhook_url = site_url('/wp-json/wx-bot-master/webhook/telegram.json');

        try {
            $bot = ChatBot::factory(ChatBot::TELEGRAM_ID);

            $bot->setupWebhook($webhook_url);

            $this->setMessages(
                Config::TELEGRAM_API_TOKEN,
                __('Webhook has been set up successfully', 'wp_bot_master') . '. Webhook link: ' . $webhook_url
            );

        } catch (\Throwable $e) {
            $this->setError(
                Config::TELEGRAM_API_TOKEN,
                $e->getMessage()
            );

            if (!Config::isDebug()) {
                return;
            }

            $this->setError(
                Config::TELEGRAM_API_TOKEN . '_trace',
                $trace = nl2br($e->getTraceAsString())
            );
        }
    }

    /**
     *
     */
    public function getRequest()
    {

    }
}
