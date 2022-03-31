<?php

namespace WebXID\BotsMaster\ChatBot;

use WebXID\BotsMaster\ChatBot;
use Longman\TelegramBot;
use WebXID\BotsMaster\ChatBot\DataContainers\BotResponse;
use WebXID\BotsMaster\Config;
use function WebXID\BotsMaster\___dump;
use function WebXID\BotsMaster\config;

/**
 * @property TelegramBot\Telegram client
 * @property array request
 * Array(
 *      [update_id] => 8187
 *      [message] => Array
 *      (
 *          [message_id] => 2230
 *          [from] => Array
 *          (
 *              [id] => 23525
 *              [is_bot] =>
 *              [first_name] => Pavlo M.
 *              [username] => tym
 *              [language_code] => uk
 *          )
 *
 *          [chat] => Array
 *          (
 *              [id] => 23525
 *              [first_name] => Pavlo M.
 *              [username] => tym
 *              [type] => private
 *          )
 *
 *          [date] => 16378
 *          [text] => /start test
 *          [entities] => Array
 *          (
 *              [0] => Array
 *              (
 *                  [offset] => 0
 *                  [length] => 6
 *                  [type] => bot_command
 *              )
 *          )
 *      )
 * )
 */
class Telegram extends ChatBot
{
    /**
     * @inheritDoc
     */
    public static function build(?BotUser $bot_user): ChatBot
    {
        if (!config(Config::TELEGRAM_API_TOKEN)) {
            throw new \LogicException('Invalid telegram bot API token');
        }

        $object = static::make([
            'client' => new TelegramBot\Telegram(config(Config::TELEGRAM_API_TOKEN)),
            'bot_user' => $bot_user,
        ]);

        return $object;
    }

    #region Actions

    /**
     * @inheritDoc
     */
    public function setupWebhook(string $url): ChatBot
    {
        if (!$url) {
            throw new \InvalidArgumentException('Invalid $url');
        }

        /** @var TelegramBot\Entities\ServerResponse $responce */
        $responce = $this->client->setWebhook($url);

        if (!$responce->isOk()) {
            throw new \RuntimeException('Webhook has not been set. Message: ' . $responce->getDescription());
        }

        return $this;
    }

    /**
     * @see https://telegram-bot-sdk.readme.io/reference/sendmessage
     * @inheritDoc
     */
    public function send(BotResponse $message): ChatBot
    {
        if (!$this->bot_user) {
            throw new \LogicException('Invalid bot_user');
        }

        $text = str_ireplace('\"', '"', $message->message);

        $text = preg_replace("/<(p|div)[^>]( *)?>/", "", $text);
        $text = str_replace(["</p>", "</div>"], "\n", $text);

        $text = strip_tags($text, '<br><a><b><i><strong><em><code><pre>');
        $text = str_ireplace(["<br />","<br>","<br/>"], "\n", $text);
        $text = str_ireplace('&nbsp;', " ", $text);
        $text = preg_replace("/<([a-z][a-z0-9]*)[^>]*?(\/?)>/si",'<$1$2>', $text); // remove all attributes

        $responce = TelegramBot\Request::sendMessage([
            'chat_id' => $this->bot_user->provider_user_id,
            'text' => $text,
            'parse_mode' => $message->parse_mode,
        ]);

        if (!$responce->isOk()) {
            throw new \RuntimeException($responce->getDescription());
        }

        return $this;
    }

    #endregion

    #region Getters

    /**
     * @inheritDoc
     */
    public function getProviderId(): int
    {
        return static::TELEGRAM_ID;
    }

    /**
     * @inheritDoc
     */
    public function getProviderUserId()
    {
        if (!$this->request || empty($this->request['message']['from']['id'])) {
            if ($this->bot_user) {
                return $this->bot_user->provider_user_id;
            }

            throw new \LogicException('Invalid request data');
        }

        return $this->request['message']['from']['id'];
    }

    /**
     * @inheritDoc
     */
    public static function getProviderUserIdFromRequest(array $request)
    {
        return $request['message']['from']['id'] ?? $request['callback_query']['from']['id'];
    }

    /**
     * @inheritDoc
     */
    public static function getFirstNameFromRequest(array $request)
    {
        return $request['message']['from']['first_name'] ??  $request['callback_query']['from']['first_name'];
    }

    /**
     * @inheritDoc
     */
    public static function getLastNameFromRequest(array $request)
    {
        return $request['message']['from']['last_name'] ??  $request['callback_query']['from']['last_name'];
    }

    /**
     * @inheritDoc
     */
    public static function getUsernameFromRequest(array $request)
    {
        return $request['message']['from']['username'] ??  $request['callback_query']['from']['username'];
    }

    /**
     * @inheritDoc
     */
    public static function getDeepLinkMarkFromRequest(array $request): ?string
    {
        if (
            empty($request['message']['text'])
            || strpos($request['message']['text'], ' ') === false
            || strpos($request['message']['text'], '/start') === false
        ) {
            return null;
        }

        [$command, $deeplink_mark] = explode(' ', $request['message']['text'], 2);

        if ($command !== '/start') {
            return null;
        }

        return base64_decode($deeplink_mark) ?: null;
    }

    /**
     * @inheritDoc
     */
    public static function getLocaleFromRequest(array $request) : string
    {
        switch ($request['message']['from']['language_code'] ??  $request['callback_query']['from']['language_code'])  {
            case 'uk':
                return 'ua';

            default:
                return get_locale();
        }
    }

    /**
     * @param array $request
     *
     * @return string|null
     */
    public static function getMessageFromRequest(array $request): ?string
    {
        $message = $request['message']['text'] ?? null;

        if (!$message) {
            return null;
        }

        if ($message[0] === '/') {
            return null;
        }

        return $message;
    }

    /**
     * @param array $request
     *
     * @return string|null
     */
    public static function getCallbackDataFromRequest(array $request): ?string
    {
        $callback_data = $request['callback_query']['data'] ?? null;

        if ($callback_data) {
            return $callback_data;
        }

        if ($request['message']['text'][0] === '/') {
            return trim($request['message']['text'], '/');
        }

        return null;
    }

    /**
     * @param string|null $deeplink_mark
     *
     * @return string
     */
    protected static function getLink(string $deeplink_mark = null): string
    {
        throw new \BadMethodCallException('The method does not implemented');

        if (!$deeplink_mark) {
            return 'https://t.me/' . '';
        }

        return 'https://t.me/' . '' . '?start=' . $deeplink_mark;
    }

    #endregion
}
