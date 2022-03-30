<?php

namespace WebXID\BotsMaster\Controllers;

use WebXID\BotsMaster\ChatBot;
use WebXID\EDMo\AbstractClass\BasicDataContainer;

class WebHookController extends BasicDataContainer
{
    /**
     * @param \WP_REST_Request $data
     *
     * @return array
     * @throws \Throwable
     */
    public function telegram($request = null): array
    {
        $request_data = $request->get_json_params() ?? [];

        if (!$request_data) {
            return [
                'status' => false,
                'message' => 'empty request',
            ];
        }

        $provider_user_id = ChatBot\Telegram::getProviderUserIdFromRequest($request_data);

        if (!$provider_user_id) {
            return [
                'status' => false,
                'message' => 'Invalid request',
            ];
        }

        try {
            /** @var ChatBot\BotUser $bot_user */
            $bot_user = ChatBot\BotUser::get([
                'provider_user_id' => $provider_user_id,
                'provider_id' => ChatBot::TELEGRAM_ID,
            ]);

            if (!$bot_user) {
                $bot_user = ChatBot\BotUser::make([
                    'provider_user_id' => $provider_user_id,
                    'provider_id' => ChatBot::TELEGRAM_ID,
                ]);
            }

            $bot_user->first_name = ChatBot\Telegram::getFirstNameFromRequest($request_data);
            $bot_user->last_name = ChatBot\Telegram::getLastNameFromRequest($request_data);
            $bot_user->username = ChatBot\Telegram::getUsernameFromRequest($request_data);
            $bot_user->locale = ChatBot\Telegram::getLocaleFromRequest($request_data);

            $bot_user->save();

            $chat_bot = ChatBot::factory($bot_user);

            ChatBot\RequestHandler::build($request_data, $chat_bot)
                ->process();

            return [];
        } catch (ChatBot\Exceptions\ResponseErrorException $e) {
            wx_log($e);
            $chat_bot->sendError($e->getMessage());

            return [];
        } catch (\Throwable $e) {
            wx_log($e);

            return [
                'status' => false,
                'message' => 'Internal Server Error',
            ];
        }
    }
}
