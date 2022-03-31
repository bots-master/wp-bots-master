<?php

namespace WebXID\BotsMaster;

use WebXID\BotsMaster\ChatBot\BotUser;
use WebXID\BotsMaster\ChatBot\DataContainers\BotResponse;
use WebXID\BotsMaster\ChatBot\Telegram;
use WebXID\EDMo\AbstractClass\BasicDataContainer;

/**
 * @property object client
 * @property ?BotUser bot_user
 * @property array request
 */
abstract class ChatBot extends BasicDataContainer
{
    const TELEGRAM_ID = 1;

    #region Builders

    /**
     * @param int|BotUser $provider_id
     *
     * @return static
     */
    public static function factory($provider_id)
    {
        $bot_user = null;

        if ($provider_id instanceof BotUser) {
            $bot_user = $provider_id;
            $provider_id = $bot_user->provider_id;
        }

        switch ($provider_id) {
            case static::TELEGRAM_ID:
                return Telegram::build($bot_user);

            default:
                throw new \InvalidArgumentException('Invalid $bot_type');
        }
    }

    #endregion

    #region Abstract methods

    /**
     * @param BotUser|null $bot_user
     *
     * @return static
     */
    abstract public static function build(?BotUser $bot_user): self;

    /**
     * @return $this
     */
    abstract public function setupWebhook(string $url): self;

    /**
     * @return int
     */
    abstract public function getProviderId(): int;

    /**
     * @return int|string
     */
    abstract public function getProviderUserId();

    /**
     * @param array $request
     *
     * @return int|string|null
     */
    abstract public static function getProviderUserIdFromRequest(array $request);

    /**
     * @param array $request
     *
     * @return string|null
     */
    abstract public static function getFirstNameFromRequest(array $request);

    /**
     * @param array $request
     *
     * @return string|null
     */
    abstract public static function getLastNameFromRequest(array $request);

    /**
     * @param array $request
     *
     * @return string|null
     */
    abstract public static function getUsernameFromRequest(array $request);

    /**
     * @param array $request
     *
     * @return int|string|null
     */
    abstract public static function getDeepLinkMarkFromRequest(array $request): ?string;

    /**
     * @param array $request
     *
     * @return string
     */
    abstract public static function getLocaleFromRequest(array $request): string;

    /**
     * @param array $request
     *
     * @return string|null
     */
    abstract public static function getMessageFromRequest(array $request): ?string;

    /**
     * @param array $request
     *
     * @return string|null
     */
    abstract public static function getCallbackDataFromRequest(array $request): ?string;

    /**
     * @param string|null $deeplink_mark
     *
     * @return string
     */
    abstract protected static function getLink(string $deeplink_mark = null): string;

    /**
     * @param BotResponse $message
     *
     * @return $this
     */
    abstract public function send(BotResponse $message): self;

    #endregion

    #region Actions

    /**
     * @param string $message
     */
    public function sendError(string $message)
    {
        $this->send(BotResponse::make(['message' => $message]));
    }

    /**
     * @param string
     */
    public function sendMessage(string $message)
    {
        $this->send(BotResponse::make(['message' => $message]));
    }

    #endregion

    #region Getters

    /**
     * @param string|null $deeplink_mark
     *
     * @return string
     */
    public static function getBotLink(int $provider_id, string $deeplink_mark = null): string
    {
        if ($deeplink_mark) {
            $deeplink_mark = base64_encode($deeplink_mark);
        }

        return static::factory($provider_id)
            ->getLink($deeplink_mark);
    }

    #endregion
}
