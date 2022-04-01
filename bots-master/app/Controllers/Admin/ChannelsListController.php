<?php

namespace WebXID\BotsMaster\Controllers\Admin;

use WebXID\BotsMaster\ChatBot;
use WebXID\BotsMaster\Config;
use WebXID\EDMo\Rules;

class ChannelsListController extends BasicController
{
    /**
     *
     */
    public function loadPage()
    {
        $this->view('admin/channels_list', [
            'chats_list' => array_map(
                function (ChatBot\BotUser $user) {
                    return esc_textarea(trim($user->provider_user_id, '@'));
                },
                ChatBot\BotUser::find(['type_id' => ChatBot\BotUser::TYPE_CHAT])
            ),
        ]);
    }

    /**
     *
     */
    public function postRequest()
    {
        if (empty($_POST['chats']) || !is_array($_POST['chats'])) {
            ChatBot\BotUser::remove(['type_id' => ChatBot\BotUser::TYPE_CHAT]);

            $this->setMessages('chats', 'All chats has been removed');

            return;
        }

        try {
            $count = 0;
            $for_removal = ChatBot\BotUser::find(['type_id' => ChatBot\BotUser::TYPE_CHAT]);

            $rules = Rules::filterRulesData(['username' => true], ChatBot\BotUser::getRules());

            foreach ($_POST['chats'] as $index => $chat_username) {
                $chat_username = sanitize_text_field(trim($chat_username, '@'));

                foreach ($for_removal as $key => $chat) {
                    if ($chat_username != $chat->username) {
                        continue;
                    }

                    unset($for_removal[$key]);
                }

                if (!$chat_username) {
                    continue;
                }

                if (!$rules->isValid(['username' => $chat_username])) {
                    $this->setError($index, $chat_username . ': ' . $rules->validation->getFirstError());

                    return;
                }

                ChatBot\BotUser::addNewOrUpdate([
                    'provider_id' => ChatBot::TELEGRAM_ID,
                    'provider_user_id' => '@' . $chat_username,
                    'first_name' => $chat_username,
                    'username' => $chat_username,
                    'type_id' => ChatBot\BotUser::TYPE_CHAT,
                ]);

                $count ++;
            }

            $for_removal
                && ChatBot\BotUser::remove([
                    'provider_user_id' => array_map(function (ChatBot\BotUser $user) {
                        return $user->provider_user_id;
                    }, $for_removal),
                ]);

            if (!$this->hasError()) {
                $this->setMessages('chats', 'Saved successfully');
            }
        } catch (\Throwable $e) {
            $this->setError('chats', $e->getMessage());

            if (Config::isDebug()) {
                $this->setError('chats_error', nl2br($e->getTraceAsString()));
            }
        }
    }

    /**
     *
     */
    public function getRequest()
    {

    }
}
