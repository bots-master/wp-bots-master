This is readme file for development.
The plugin source is here [./bots-master](./bots-master)

To get "How To Use" the plugin, place check [./bots-master/README.txt](./bots-master/README.txt)


# Install

1. Clone the repo
2. Run `composer install`
3. Activate the plugin in Debelopment Mode
4. Follow the [./bots-master/README.txt](./bots-master/README.txt) to setup the plugun in WP Admin

# How To Use

## To get subscribers and/or chats list

```php
use WebXID\BotsMaster\ChatBot;
use WebXID\BotsMaster\ChatBot\BotUser;

BotUser::find(['type_id' => ChatBot\BotUser::TYPE_CHAT]); // returns all Group Chats and Channels list
BotUser::find(['type_id' => ChatBot\BotUser::TYPE_USER]); // returns all subscribers

BotUser::all(); // returns all records
```

## To send message to a subsctiber or a chat

```php
use WebXID\BotsMaster\ChatBot;
use WebXID\BotsMaster\ChatBot\BotUser;
$bot_user = BotUser::findOne(['type_id' => ChatBot\BotUser::TYPE_USER]);

$bot_user
    && ChatBot::factory($user)
        ->sendMessage('<b>Hello</b> world!'); // will send the message, if the plugin installed correctly. @see https://core.telegram.org/bots/api#html-style
```

## To add new Admin page

Please, check folder [./app/Admin/Controllers](./app/Admin/Controllers)

```php
use WebXID\BotsMaster\Admin\MenuRegistrer;
use WebXID\BotsMaster\Admin\Controllers\BasicController;

class MyController extends BasicController
{
    public function loadPage()
    {
        // echo 'Page content';
        $this->view('admin/send_message', []); // see `./views` folder
    }

    public function postRequest()
    {}

    public function getRequest()
    {}
}

// -----------------------

MenuRegistrer::make()
    ->menuTitle(__( 'Bot Master', 'bots_master' ))
        ->subMenuTitle('🚀️ ' . __( 'Send Message', 'bots_master' ))
        ->pageTitle('🚀 ' . __( 'Send message', 'bots_master' ))
    ->capability('manage_options')
    ->slug($parent_menu_slug)
    ->iconUrl(plugins_url(WX_BOTS_MASTER_DIR . 'assets/images/icon.svg'))
    ->requestHendler(MyController::class)
    ->register();

MenuRegistrer::childTo($parent_menu_slug)
    ->menuTitle('📢 ' . __( 'Channels', 'bots_master' ))
    ->pageTitle('📢 ' . __( 'Channels', 'bots_master' ))
    ->slug('bots-master-channels-list')
    ->requestHendler(MyController::class)
    ->capability('manage_options')
    ->register();
```

## Debug Telegram requests

The webhook controller is here `\WebXID\BotsMaster\Controllers\WebHookController::telegram()`;

To dump a data use the next functions

```php
use function WebXID\BotsMaster\_log;
use function WebXID\BotsMaster\_log_and_clean; 
use function WebXID\BotsMaster\_log_and_die;
use function WebXID\BotsMaster\_log_clean_die;

_log($data); // Adds Dump of the $data var to the end of log file and continue a script procssing
_log_and_clean($data); // Cleaned up the log file, Dumps the $data var and continue a script procssing
_log_and_die($data); // Adds Dump of the $data var to the end of log file and break a script processing
_log_clean_die($data); // Cleaned up the log file, Adds Dump of the $data var to the end of log file and break a script processing
```

The log file route: `ABSPATH . '/logs/webxid.log'`.

!!! Note !!!

The dir `logs` has to be readable and writable for the script. Otherwise you will get a permission error.
