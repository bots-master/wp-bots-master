<?php

namespace WebXID\BotsMaster\ChatBot;

use WebXID\BotsMaster\ChatBot;
use WebXID\BotsMaster\ChatBot\DataContainers\BotResponse;
use WebXID\BotsMaster\Config;
use WebXID\EDMo\AbstractClass\BasicDataContainer;

/**
 * @property ChatBot chat_bot
 * @property array request_data
 */
class RequestHandler extends BasicDataContainer
{
    #region Builders

    /**
     * @param ChatBot $chat_bot
     *
     * @return static
     */
    public static function build(array $request_data, ChatBot $chat_bot): self
    {
        $handler = static::make([
            'chat_bot' => $chat_bot,
            'request_data' => $request_data,
        ]);

        return $handler;
    }

    #endregion

    #region Actions

    /**
     * @return $this
     */
    public function process()
    {
        if ($this->getDeepLinkMark()) {
            $this->processDeeplink();
        }

        if ($this->getCallbackData()) {
            $this->processCallbackData();

            return $this;
        }

        if ($this->getMessage()) {
            $this->processMessage();
        }

        return $this;
    }

    /**
     * @return void
     */
    private function processDeeplink()
    {
        $access_token = $this->getDeepLinkMark();

        if (!$access_token) {
            throw new \LogicException('Invalid data in DeepLink processing');
        }
    }

    /**
     * @return void
     */
    private function processMessage()
    {
        $message = $this->getMessage();

        if (!$message) {
            throw new \LogicException('Invalid data in Message processing');
        }

        $this->chat_bot->send(BotResponse::make([
            'message' => Config::get(Config::UNKNOWN_MESSAGE),
        ]));
    }

    /**
     * @return void
     */
    private function processCallbackData()
    {
        $callback_data = $this->getCallbackData();

        [$command, $callback_action] = explode(":", $callback_data);

        switch($command){
            case 'start':
                $this->chat_bot->send(BotResponse::make([
                    'message' => Config::get(Config::WELCOME_MESSAGE),
                ]));
                break;

            default:
                $this->chat_bot->send(BotResponse::make([
                    'message' => Config::get(Config::UNKNOWN_MESSAGE),
                ]));
        }
    }

    #endregion

    #region Is Condition methods

    #endregion

    #region Getters

    /**
     * @return bool
     */
    private function getDeepLinkMark(): ?string
    {
        return $this->chat_bot::getDeepLinkMarkFromRequest($this->request_data);
    }

    /**
     * @return string
     */
    public function getLocale(): string
    {
        return $this->chat_bot::getLocaleFromRequest($this->request_data);
    }

    /**
     * @return string|null
     */
    private function getMessage()
    {
        return $this->chat_bot::getMessageFromRequest($this->request_data);
    }

    /**
     * @return string|null
     */
    private function getCallbackData()
    {
        return $this->chat_bot::getCallbackDataFromRequest($this->request_data);
    }

    #endregion
}
