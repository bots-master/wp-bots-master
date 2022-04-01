<?php

namespace WebXID\BotsMaster\ChatBot;

use WebXID\EDMo\AbstractClass\MultiKeyModel;
use WebXID\EDMo\Rules;

/**
 *
 * @property int provider_id
 * @property string provider_user_id
 * @property string first_name
 * @property string last_name
 * @property string username
 * @property string locale
 * @property string type_id
 */
class BotUser extends MultiKeyModel
{
    const TABLE_NAME = 'wx_bot_users'; // Allows to contain single table only
    const JOINED_TABLES = 'wx_bot_users bu'; // Allows to contain single table name and/or joined tables with `ON` and `WHERE` sections

    const TYPE_USER = 1;
    const TYPE_CHAT = 2; // channel or group

    /** @var array */
    protected static $columns = [
        'provider_id' => ['type' => 'int'],
        'provider_user_id' => ['type' => 'string', 'length' => 256],
        'first_name' => ['type' => 'string', 'length' => 256],
        'last_name' => ['type' => 'string', 'length' => 256],
        'username' => ['type' => 'string', 'length' => 256],
        'locale' => ['type' => 'string', 'length' => 4],
        'type_id' => ['type' => 'int', 'length' => 3],
    ];

    /** @var array */
    protected static $joined_columns_list = [
        'provider_id',
        'provider_user_id',
        'first_name',
        'last_name',
        'username',
        'locale',
        'type_id',
    ];

    /** @var array */
    protected static $readable_properties = [
        'provider_id' => true,
        'provider_user_id' => true,
        'first_name' => true,
        'last_name' => true,
        'username' => true,
        'locale' => true,
        'type_id' => true,
    ];

    /** @var array */
    protected static $writable_properties = [
        'first_name' => true,
        'last_name' => true,
        'username' => true,
        'locale' => true,
    ];


    #region Getters

    /**
     * @inheritDoc
     */
    protected function getUniqueKeyConditions() : array
    {
        return [
            'provider_id' => $this->provider_id,
            'provider_user_id' => $this->provider_user_id,
        ];
    }

    /**
     * @inheritDoc
     */
    public static function getRules() : Rules
    {
        return Rules::make([
            'username' => Rules\Field::string([
                Rules\Type::itRequired('username is required'),
                Rules\Type::regexp('/^(@)?[a-zA-Z0-9_]+$/', __('`username` contains disallowed chars', 'bots_master')),
            ]),
        ]);
    }

    /**
     * @return string
     */
    public function getFullName()
    {
        return "{$this->first_name} {$this->last_name}";
    }

    #endregion
}
