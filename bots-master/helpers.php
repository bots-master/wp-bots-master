<?php

namespace WebXID\BotsMaster;

/**
 * @param string $tpl_file_name
 * @param array $data
 */
function includeTpl(string $tpl_file_name, array $data = [])
{
    echo Tpl::render($tpl_file_name, $data);
}

/**
 * @param string $key
 * @param null $default
 *
 * @return mixed|null
 */
function config(string $key, $default = null)
{
    return Config::get($key, $default);
}

function _log_clean_die($data, $has_die = true, $clean_log = true, $debug_backtrace = null)
{
    if ($data instanceof \Throwable) {
        $data = [
            'message' => $data->getMessage(),
            'trace' => $data->getTraceAsString(),
        ];
    }

    $debug_backtrace = $debug_backtrace ?? debug_backtrace();

    $log_filename = ABSPATH . '/logs';

    if (!file_exists($log_filename))
    {
        // create directory/folder uploads.
        mkdir($log_filename, 0777, true);
    }

    $log_route = $log_filename . '/webxid.log';

    if ($clean_log && is_file($log_route)) {
        unlink($log_route);
    }

    // if you don't add `FILE_APPEND`, the file will be erased each time you add a log
    error_log(
        "\n" .
        "----------------\n" .
        print_r($data, true) . (!is_array($data) ? "\n" : '')  .
        "----------------\n" .
        "Route: " . $debug_backtrace[0]['file'] . ':' . $debug_backtrace[0]['line'] . "\n" .
        "\n",
        3,
        $log_route);

    $has_die && exit;
}

function _log($data)
{
    _log_clean_die($data, false, false,  debug_backtrace());
}

function _log_and_die($data)
{
    _log_clean_die($data, true, false,  debug_backtrace());
}

function _log_and_clean($data)
{
    _log_clean_die($data, false, true,  debug_backtrace());
}
