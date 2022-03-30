<?php

namespace WebXID\BotsMaster\ChatBot\DataContainers;

use WebXID\EDMo\AbstractClass\BasicDataContainer;

/**
 * @property string image
 * @property string message
 * @property string parse_mode
 */
class BotResponse extends BasicDataContainer
{
    protected $parse_mode = 'HTML';

    /**
     * @return string
     */
    public function getImage() : string
    {
        if (config('app.debug')) {
//            return 'https://via.placeholder.com/300/09f/fff.png';
        }

        return $this->image;
    }
}
