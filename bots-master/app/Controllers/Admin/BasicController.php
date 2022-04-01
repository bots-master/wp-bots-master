<?php

namespace WebXID\BotsMaster\Controllers\Admin;

use WebXID\EDMo\AbstractClass\BasicDataContainer;
use WebXID\EDMo\Validation\Error;
use function WebXID\BotsMaster\includeTpl;

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

    #region Is Condition Mthods

    /**
     * @return bool
     */
    protected function hasError(): bool
    {
        return $this->errors->isNotEmpty();
    }

    #endregion

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
        $this->errors->add($param_name, __($message, 'bots_master'));
    }

    #endregion

    /**
     * @param string $message
     */
    protected function view(string $tpl_file_name, array $data = [])
    {
        includeTpl($tpl_file_name, [
                'wx_errors' => $this->errors,
                'wx_messages' => $this->messages,
        ] + $data);
    }
}
