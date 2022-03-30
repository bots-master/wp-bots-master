<?php

use WebXID\BotMaster\Config;
use WebXID\BotMaster\Tpl;

if (!class_exists ('env')) {

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

}

#region Dump functions

if (!function_exists('_dump')){
    /**
     * Print string in draggable popup window
     *
     * @see ___dump() - Use this function if you are offline and/or _dump() doesn't work correct
     *
     * @param  string | array  $str       - string, what will print
     * @param  string  $var_title - title of window	 * @param  boolean $exit      - does script need to exit?
     * @param  boolean $details   - print string throught pring_r (default) or var_dump
     */
    function _dump($str, $var_title = 1, $exit = true, $var_dump = FALSE, $title_style = '#d3d3d3')
    {
        if (!$var_dump){
            $buff = print_r($str, TRUE);
        }else{
            ob_start();
            var_dump($str);
            $buff = ob_get_clean();
            ob_end_clean();
        }

        $debug_backtrace = debug_backtrace();
        $file_route = '<div style="font-size:12px;border-top: 1px solid #898888;margin: 10px -10px -10px;padding: 10px 10px 11px;background: #e7e7e7;width: 100%;">Route: <input style="width: 190px;" value="' . $debug_backtrace[0]['file'].':'.$debug_backtrace[0]['line'] . '"/></div>';

        if($var_title == 1) {
            $var_title = '<b>#' . $debug_backtrace[0]['line'] . '</b>';
        }


        switch ($title_style) {
            case -1:
                $title_style = '#FFCBCB';
                break;

            default:
                $title_style = '#d3d3d3';
                break;
        }

        $dumps_html = '
	<style type="text/css">
			.dump-cl {
					min-width: 250px !important;
					height: auto !important;
					width: auto !important;
					max-width: 90% !important;
					font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
			}
			.dump-cl pre{
					padding: 0;
					margin: 0;
					font-size: 13px;
					line-height: normal;
					word-break: normal;
					word-wrap: normal;
					background: none;
					border: 0;
					border-radius: 0;
					font-weight: normal;
					white-space: pre-wrap;
			}
	</style>

	<div class="dump-cl" style="top: 10px;left: 10px; position: absolute;z-index: 999999;border-radius: 10px;overflow: hidden;background-color: white;box-shadow: 1px 1px 5px #000;">
			<div class="dump-h" style="background-color: ' . $title_style .';padding: 10px 10px;border-bottom: 1px solid #000;vertical-align: top;cursor: move;"><span class="dump-hide" style="cursor:pointer;font-size: 35px;position: absolute;top: -3px;right: 0;padding: 0px 12px 3px;display: block;">-</span>
				<span style="margin-right: 15px;">' . $var_title . '</span></div>
			<div class="dump-cont" style="padding: 10px;">
					<pre style="margin: 0;">'
            . $buff .
            '</pre>
					'.$file_route.'
			</div>
	</div>';

        if (!defined('_DUMPS_')){

            define('_DUMPS_','1');
            $dumps_html .= '<script type="text/javascript">
			if(!window.jQuery){
					document.writeln(
							\'<sc\'+\'ript sr\'+\'c="https://ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"><\'+\'/\'+\'sc\'+\'ript\'+\'>\'
					);
					document.writeln(
							\'<link rel="stylesheet" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/themes/smoothness/jquery-ui.css">\'+
							\'<sc\'+\'ript sr\'+\'c="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"><\'+\'/\'+\'sc\'+\'ript\'+\'>\'
					);
			}
			/*if (typeof jQuery == "function" && (\'ui\' in jQuery) && jQuery.ui && (\'version\' in jQuery.ui))
			{

			}*/
	</script>
	<script type="text/javascript">
			jQuery(document).ready(function(){
					jQuery( ".dump-cl" ).draggable({ handle: ".dump-h" });
					jQuery( ".dump-cl" ).click(function(){
						$(".dump-cl").css("z-index", "999999");
						$(this).css("z-index", "9999999");
					});
					jQuery(".dump-h .dump-hide").click(function(){
							jQuery(this).parents(".dump-cl").find(".dump-cont").toggle("slow");
					});
			});

			</script>';
        }

        echo $dumps_html;

        if ($exit) {
            exit();
        }
    }

}


if (!function_exists('__dump')){

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

}


if (!function_exists('___dump')){

    /**
     * Makes dump which is adapt for review on browser web page and in page source;
     * Use this function if you are offline and/or _dump() doesn't work correct
     *
     * @return void
     */
    function ___dump($str, $var_title = 1, $exit = true, $var_dump = FALSE)
    {
        if (!env::isAllowedIP()) {
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
            "Loaded in: " . _trackTime('script_load_time') . "\n" .
            "\n\n</pre>";

        echo $dumps_html;

        if ($exit) {
            exit();
        }
    }

}

#endregion

#region Trace

if (!function_exists('_trace')) {

    /**
     * Makes dump which is adapt for review on browser web page and in page source;
     * Use this function if you are offline and/or _dump() doesn't work correct
     *
     * @return void
     */
    function _trace($data, $var_title = 1, $exit = true, $var_dump = FALSE, $dump_function = '_dump')
    {
        try {
            $data = json_encode($data);

            throw new InvalidArgumentException($data);
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


            $dump_function([
                'message' => json_decode($e->getMessage(), TRUE),
                'trace' => $trace,
            ], $var_title, $exit, $var_dump);
        }
    }

}

if (!function_exists('__trace')){

    /**
     * Makes dump which is adapt for review on browser web page and in page source;
     * Use this function if you are offline and/or _dump() doesn't work correct
     *
     * @return void
     **/
    function __trace($data, $var_title = 1, $exit = true, $var_dump = FALSE)
    {
        _trace($data, $var_title, $exit, $var_dump, '__dump');
    }

}

if (!function_exists('___trace')){

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

}

#endregion

#region Tracking functions

if (!function_exists('_mTime')){

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

}

if (!function_exists('_trackTime')){

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
            throw new InvalidArgumentException('Invalid $script_key');
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
            $trackTime[$script_key]['start'] = _mTime();
            $trackTime[$script_key]['time'] = 0;
        }else{
            $trackTime[$script_key]['time'] = (_mTime() - $trackTime[$script_key]['start']);

            return number_format($trackTime[$script_key]['time'], $number_size) . ($show_unit ? ' s' : '');
        }

        return number_format($trackTime[$script_key]['time'], $number_size) . ($show_unit ? ' s' : '');
    }

}


if (!function_exists('_trackMemory')) {

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
            throw new InvalidArgumentException('Invalid $script_key');
        }

        global $trackMemory;

        if ($script_key === true) {
            $result = '';

            if ($trackMemory) {
                foreach ($trackMemory as $script_key) {
                    $result .= $script_key . ': ' . _trackMemory($script_key, $show_unit) . "\n";
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

}

if (!function_exists('_track')) {
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
}

#endregion

_trackTime('script_load_time');


if (!function_exists('wx_includeTpl')) {
    /**
     * @param string $tpl_file_name
     * @param array $data
     */
    function wx_includeTpl(string $tpl_file_name, array $data = [])
    {
        echo Tpl::render($tpl_file_name, $data);
    }
}

if (!function_exists('wx_config')) {
    /**
     * @param string $key
     * @param null $default
     *
     * @return mixed|null
     */
    function wx_config(string $key, $default = null)
    {
        return Config::get($key, $default);
    }
}

if (!function_exists('wx_log')) {
    function wx_log_clean_die($data, $has_die = true, $clean_log = true, $debug_backtrace = null)
    {
        if ($data instanceof Throwable) {
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

    function wx_log($data)
    {
        wx_log_clean_die($data, false, false,  debug_backtrace());
    }

    function wx_log_and_die($data)
    {
        wx_log_clean_die($data, true, false,  debug_backtrace());
    }

    function wx_log_and_clean($data)
    {
        wx_log_clean_die($data, false, true,  debug_backtrace());
    }
}
