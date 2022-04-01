<?php

namespace WebXID\BotsMaster;

#region Debug stuff

class env {
    static private $allowed_ips = NULL;

    static public function setAllowedIPs($ips_list)
    {
        if ($ips_list) {
            self::$allowed_ips = (array)$ips_list;
        }
    }

    static public function isAllowedIP()
    {
        if (self::$allowed_ips) {
            if (in_array(self::getIP(), self::$allowed_ips)) {
                return true;
            }

            return false;
        } else {
            return true;
        }
    }

    static public function getIP()
    {
        if(isset($_SERVER['HTTP_X_REAL_IP'])) {
            return $_SERVER['HTTP_X_REAL_IP'];
        }
        return $_SERVER['REMOTE_ADDR'];
    }
}

#region Dump functions

/**
 * Makes dump which is adapt for review in browser consol log  - consol.log(dump_data);
 *
 * @return string|false|void
 **/
function __dump($str, $var_title = 1, $exit = true, $var_dump = FALSE, $echo = TRUE)
{
    if (!$var_dump) {
        $buff = print_r($str, true);
    } else {
        ob_start();
        var_dump($str);
        $buff = ob_get_clean();
    }

    $debug_backtrace = debug_backtrace();
    $file_route = 'Route: ' . $debug_backtrace[0]['file'] . ':' . $debug_backtrace[0]['line'];

    if ($var_title == 1) {
        $var_title = 'line #' . $debug_backtrace[0]['line'] . '';
    }

    $dumps_html = "\nDeBuging Dump\n=============\nTitle: " . $var_title . "\n" . "===================================\n\n" . htmlentities($buff) . "\n\n" . "===================================\n" . $file_route . "\n" . "\n\n";

    if ($echo) {
        echo $dumps_html;
    } else {
        return $dumps_html;
    }

    if ($exit) {
        exit();
    }
}


/**
 * Makes dump which is adapt for review on browser web page and in page source;
 * Use this function if you are offline and/or _dump() doesn't work correct
 *
 * @return void
 */
function ___dump($str, $var_title = 1, $exit = true, $var_dump = FALSE)
{
    if (!\WebXID\BotsMaster\env::isAllowedIP()) {
        return;
    }

    if (!$var_dump) {
        $buff = print_r($str, true);
    } else {
        ob_start();
        var_dump($str);
        $buff = ob_get_clean();
    }

    $debug_backtrace = debug_backtrace();
    $file_route = 'Route: ' . $debug_backtrace[0]['file'] . ':' . $debug_backtrace[0]['line'];

    if ($var_title == 1) {
        $var_title = 'line #' . $debug_backtrace[0]['line'] . '';
    }

    $dumps_html =
        "<pre style=\"margin: 0;\">\n" .
        "DeBuging Dump\n" .
        "=============\n" .
        "Title: " . $var_title . "\n" .
        "===================================\n\n" .
        $buff . "\n\n" .
        "===================================\n" .
        $file_route . "\n" .
        "Loaded in: " . \WebXID\BotsMaster\_trackTime('script_load_time') . "\n" .
        "\n\n</pre>";

    echo $dumps_html;

    if ($exit) {
        exit();
    }
}

#endregion

#region Trace

/**
 * Makes dump which is adapt for review on browser web page and in page source;
 * Use this function if you are offline and/or _dump() doesn't work correct
 *
 * @return void
 */
function _trace($data, $var_title = 1, $exit = true, $var_dump = FALSE, $dump_function = '___dump')
{
    try {
        $data = json_encode($data);

        throw new \InvalidArgumentException($data);
    } catch (\Exception $e) {
        $trace = explode( "\n", $e->getTraceAsString());

        $full_trace = $e->getTrace();

        if (isset($full_trace[1]['function'])) {
            switch ($full_trace[1]['function']) {
                case '__trace':
                case '___trace':
                    unset($trace[0]);
            }
        }

        $dump_function = 'WebXID\BotsMaster\\' . $dump_function;

        $dump_function([
            'message' => json_decode($e->getMessage(), TRUE),
            'trace' => $trace,
        ], $var_title, $exit, $var_dump);
    }
}

/**
 * Makes dump which is adapt for review on browser web page and in page source;
 * Use this function if you are offline and/or _dump() doesn't work correct
 *
 * @return void
 **/
function __trace($data, $var_title = 1, $exit = true, $var_dump = FALSE)
{
    \WebXID\BotsMaster\_trace($data, $var_title, $exit, $var_dump, '__dump');
}

/**
 * Makes dump which is adapt for review on browser web page and in page source;
 * Use this function if you are offline and/or _dump() doesn't work correct
 *
 * @return void
 **/
function ___trace($data, $var_title = 1, $exit = FALSE, $var_dump = FALSE)
{
    _trace($data, $var_title, $exit, $var_dump, '___dump');
}

#endregion

#region Tracking functions

/**
 * Returns time in seconds with milliseconds
 *
 * @return float
 */
function _mTime()
{
    [$usec, $sec] = explode(' ', microtime());

    return (float) $usec + (float) $sec;
}

/**
 * Counting time of a script execution speed
 *
 * @example
 *    _trackTime('Time of testing');
 *        //some code
 *    echo _trackTime('Time of testing');
 *    echo _trackTime(true);
 *
 * @param string|true $script_key
 * @param int $number_size
 * @param bool $show_unit - TRUE if needs to return unit of measurement (b)
 *
 * @return string
 *
 */
function _trackTime($script_key = 'script_load_time', $number_size = 6, $show_unit = true)
{
    if (!is_string($script_key) && $script_key !== true) {
        throw new \InvalidArgumentException('Invalid $script_key');
    }

    $number_size = (int) $number_size;

    if ($number_size < 1) {
        $number_size = 6;
    }

    global $trackTime;

    if ($script_key === true) {
        $result = '';

        if ($trackTime) {
            foreach ($trackTime as $script_key => $time) {
                $result .= $script_key . ': ' . _trackTime($script_key, $number_size, $show_unit) . "\n";
            }
        }

        return $result;
    }

    if(!isset($trackTime[$script_key]['start'])){
        $trackTime[$script_key]['start'] = \WebXID\BotsMaster\_mTime();
        $trackTime[$script_key]['time'] = 0;
    }else{
        $trackTime[$script_key]['time'] = (_mTime() - $trackTime[$script_key]['start']);

        return number_format($trackTime[$script_key]['time'], $number_size) . ($show_unit ? ' s' : '');
    }

    return number_format($trackTime[$script_key]['time'], $number_size) . ($show_unit ? ' s' : '');
}


/**
 * Tracks used memory
 *
 * @example
 * 		_trackMemory('my_script'); //start it track used memory
 * 		//some code
 * 		echo _trackMemory('my_script'); //print value of used memory
 *
 * 		//Print all collected tracks
 * 		echo _trackMemory(true);
 *
 * @param string|true $script_key - title of script part
 * @param bool $show_unit - needs to return unit of measurement (b)
 *
 * @return string
 */
function _trackMemory($script_key = 'script_load_memory', $show_unit = true)
{
    if (!is_string($script_key) && $script_key !== true) {
        throw new \InvalidArgumentException('Invalid $script_key');
    }

    global $trackMemory;

    if ($script_key === true) {
        $result = '';

        if ($trackMemory) {
            foreach ($trackMemory as $script_key) {
                $result .= $script_key . ': ' . \WebXID\BotsMaster\_trackMemory($script_key, $show_unit) . "\n";
            }

            $result .= 'Peak: ' . memory_get_peak_usage() . ($show_unit ? ' b' : '');
        }

        return $result;
    }

    if (!isset($trackMemory[$script_key])) {
        $trackMemory[$script_key] = - memory_get_usage();
    } else {
        $trackMemory[$script_key] += memory_get_usage();
    }

    if ($trackMemory[$script_key] < 0) {
        return 0 . ($show_unit ? ' b' : '');
    }

    return $trackMemory[$script_key] . ($show_unit ? ' b' : '');
}

function _track($var_name = null, $value = '___print___')
{
    static $vars_log;

    if ($var_name === null) {
        return $vars_log ?? [];
    }

    if ($value !== '___print___') {
        $vars_log[$var_name][] = $value;
    }

    return $vars_log[$var_name];
}

#endregion

_trackTime('script_load_time');


#endregion
