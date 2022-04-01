<?php

namespace WebXID\BotsMaster\Controllers\Admin;

use WebXID\BotsMaster\ChatBot;
use WebXID\BotsMaster\Config;

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
        $api_key = sanitize_text_field($_POST[Config::TELEGRAM_API_TOKEN]);
        $welcome_message = wp_kses_post($_POST[Config::WELCOME_MESSAGE]);
        $unknown_message = wp_kses_post($_POST[Config::UNKNOWN_MESSAGE]);

        Config::set(Config::TELEGRAM_API_TOKEN, $api_key);
        Config::set(Config::WELCOME_MESSAGE, $welcome_message);
        Config::set(Config::UNKNOWN_MESSAGE, $unknown_message);

        $webhook_url = site_url('/wp-json/wx-bots-master/webhook/telegram.json');

        try {
            $bot = ChatBot::factory(ChatBot::TELEGRAM_ID);

            $bot->setupWebhook($webhook_url);

            $this->setMessages(
                Config::TELEGRAM_API_TOKEN,
                __('Webhook has been set up successfully', 'bots_master') . '. Webhook link: ' . $webhook_url
            );

        } catch (\Throwable $e) {
            Config::delete(Config::TELEGRAM_API_TOKEN);

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
