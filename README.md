This is readme file for development.

To get "How To Use" the plugin, place check [./README.txt](./README.txt)


# Install

1. Clone the repo
2. Run `composer install`
3. Activate the plugin in WP Admin panel - [./README.txt](./README.txt)

# How To Use

## To get subscribers and/or chats list

```php
use WebXID\BotMaster\ChatBot;
use WebXID\BotMaster\ChatBot\BotUser;

BotUser::find(['type_id' => ChatBot\BotUser::TYPE_CHAT]); // returns all Group Chats and Channels list
BotUser::find(['type_id' => ChatBot\BotUser::TYPE_USER]); // returns all subscribers

BotUser::all(); // returns all records
```

## To send message to a subsctiber or a chat

```php
use WebXID\BotMaster\ChatBot;
use WebXID\BotMaster\ChatBot\BotUser;
$bot_user = BotUser::findOne(['type_id' => ChatBot\BotUser::TYPE_USER]);

$bot_user
    && ChatBot::factory($user)
        ->sendMessage('<b>Hello</b> world!'); // will send the message, if the plugin installed correctly. @see https://core.telegram.org/bots/api#html-style
```

## To add new Admin page

Please, check folder [./app/Admin/Controllers](./app/Admin/Controllers)

```php
use WebXID\BotMaster\Admin\MenuRegistrer;
use WebXID\BotMaster\Admin\Controllers\BasicController;

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
    ->menuTitle(__( 'Bot Master', 'wp_bot_master' ))
        ->subMenuTitle('🚀️ ' . __( 'Send Message', 'wp_bot_master' ))
        ->pageTitle('🚀 ' . __( 'Send message', 'wp_bot_master' ))
    ->capability('manage_options')
    ->slug($parent_menu_slug)
    ->iconUrl(plugins_url('bot-master/assets/images/icon.svg'))
    ->requestHendler(MyController::class)
    ->register();

MenuRegistrer::childTo($parent_menu_slug)
    ->menuTitle('📢 ' . __( 'Channels', 'wp_bot_master' ))
    ->pageTitle('📢 ' . __( 'Channels', 'wp_bot_master' ))
    ->slug('bot-master-channels-list')
    ->requestHendler(MyController::class)
    ->capability('manage_options')
    ->register();
```

## Debug Telegram requests

The webhook controller is here `\WebXID\BotMaster\Controllers\WebHookController::telegram()`;

To dump a data use the next functions

```php
wx_log($data); // Adds Dump of the $data var to the end of log file and continue a script procssing
wx_log_and_clean($data); // Cleaned up the log file, Dumps the $data var and continue a script procssing
wx_log_and_die($data); // Adds Dump of the $data var to the end of log file and break a script processing
wx_log_clean_die($data); // Cleaned up the log file, Adds Dump of the $data var to the end of log file and break a script processing
```

The log file route: `ABSPATH . '/logs/webxid.log'`.

!!! Note !!!

The dir `logs` has to be readable and writable for the script. Otherwise you will get a permission error.