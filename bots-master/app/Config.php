<?php

namespace WebXID\BotsMaster;

use WebXID\EDMo\AbstractClass\BasicDataContainer;

class Config extends BasicDataContainer
{
    const TELEGRAM_API_TOKEN = 'telegram_api_token';
    const WELCOME_MESSAGE = 'welcome_message';
    const UNKNOWN_MESSAGE = 'unknown_message';


    /** @var static */
    private static $config;

    public static function init()
    {
        return static::$config = static::make([
            static::TELEGRAM_API_TOKEN => get_option( static::TELEGRAM_API_TOKEN, ''),
        ]);
    }

    #region Is Condition methods

    /**
     * @param string $key
     *
     * @return bool
     */
    public static function has(string $key)
    {
        return isset(static::$config->$key);
    }

    /**
     * @return bool
     */
    public static function isDebug(): bool
    {
        return WP_DEBUG || WX_BOTS_MASTER_DEV_MODE;
    }

    #endregion

    /**
     * @param $key
     * @param $value
     *
     * @return mixed
     */
    public static function set(string $key, $value)
    {
        update_option($key, $value);

        return static::$config->$key = $value;
    }

    /**
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    public static function get(string $key, $default = null)
    {
        $value = static::$config->$key ?? get_option($key, null);

        if ($value) {
            static::$config->$key = $value;
        }

        return $value ?? $default;
    }

    /**
     * @param string $key
     */
    public static function delete(string $key)
    {
        delete_option( $key);

        unset(static::$config->$key);
    }
}
