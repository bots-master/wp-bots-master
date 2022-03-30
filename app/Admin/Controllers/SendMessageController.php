<?php

namespace WebXID\BotsMaster\Admin\Controllers;

use WebXID\BotsMaster\ChatBot;

class SendMessageController extends BasicController
{
    /**
     *
     */
    public function loadPage()
    {
        $this->view('admin/send_message', []);
    }

    /**
     *
     */
    public function postRequest()
    {
        if (empty($_POST['message'])) {
            $this->setError('message', 'An empty message cannot be sent');

            return;
        }

        $count = 0;

        foreach (ChatBot\BotUser::all() as $user) {
            try {
                ChatBot::factory($user)
                    ->sendMessage($_POST['message']);

                $count ++;
            } catch (\RuntimeException $e) {
                $this->setError('message', "Error: " . $e->getMessage());

                break;
            } catch (\Throwable $e) {
                $this->setError($user->provider_user_id, "
                    " . $user->getFullName() . " didn't receive the message <br>
                    Error: " . $e->getMessage() . "
                ");
            }
        }

        $this->setMessages('message', "
            {$count} subscriber" . ($count > 1 ? 's' : '') . " received the message
        ");
    }

    /**
     *
     */
    public function getRequest()
    {

    }
}
