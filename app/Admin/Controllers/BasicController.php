<?php

namespace WebXID\BotMaster\Admin\Controllers;

use WebXID\EDMo\AbstractClass\BasicDataContainer;
use WebXID\EDMo\Validation\Error;

abstract class BasicController extends BasicDataContainer implements AdminControllerInterface
{
    /** @var Error */
    private $errors;
    /** @var Error */
    private $messages;

    protected function __construct()
    {
        $this->errors = Error::init();
        $this->messages = Error::init();
    }

    #region Setters

    /**
     * @param string $param_name
     * @param string $message
     */
    protected function setMessages(string $param_name, string $message)
    {
        $this->messages->add($param_name, $message);
    }

    /**
     * @param string $message
     */
    protected function setError(string $param_name, string $message)
    {
        $this->errors->add($param_name, __($message, 'wp_bot_master'));
    }

    #endregion

    /**
     * @param string $message
     */
    protected function view(string $tpl_file_name, array $data = [])
    {
        wx_includeTpl($tpl_file_name, [
                'wx_errors' => $this->errors,
                'wx_messages' => $this->messages,
        ] + $data);
    }
}
