<?php

namespace WebXID\BotsMaster;

class Tpl
{
    /**
     * Include view file
     * @param string $tpl_file_name - template file name
     * @param array $data - array of variables, what use in template
     *
     * @return string
     *
     **/
    public static function render(string $tpl_file_name, array $data = []): string
    {
        if (substr($tpl_file_name, -4) != '.php') {
            $tpl_file_name .= '.php';
        }

        $file_route = static::route('views/' . $tpl_file_name);

        extract($data, EXTR_PREFIX_INVALID, 'index');

        $___data_log = '';

        foreach ($data as $key => $value) {
            $___data_log .= '$' . $key . ";\n";
        }

        ob_start();

        require $file_route;

        $tpl_file_name = ob_get_contents();

        ob_end_clean();

        return $tpl_file_name;
    }

    /**
     * @param string $file_path
     *
     * @return string
     */
    public static function route(string $file_path): string
    {
        $file = realpath(__DIR__ . '/../' . trim($file_path, '/'));

        if (!is_file($file)) {
            throw new \InvalidArgumentException('File does not exist. ' . $file);
        }

        return $file;
    }

    /**
     * @return string
     */
    public static function pluginFile()
    {
        if (WX_BOTS_MASTER_DEV_MODE) {
            return static::route('../wp-bots-master.php');
        }

        return static::route('bots-master.php');
    }

    /**
     * @param $file
     *
     * @return string
     */
    public static function pluginUrl($file)
    {
        $besic_dir = WX_BOTS_MASTER_DEV_MODE
            ? 'wp-bots-master/bots-master'
            : 'bots-master'
        ;

        return plugins_url($besic_dir . '/' . trim($file, '/'));
    }
}
