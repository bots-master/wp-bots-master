<?php

use WebXID\BotsMaster\Config;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

wx_includeTpl('admin/parts/header', [
    'errors' => $wx_errors ?? null,
    'messages' => $wx_messages ?? null
]);

?>
<div id="bots-master" class="container float-start">
    <div class="row mb-3">
        <div class="col-12">
            <h3><?php echo get_admin_page_title(); ?></h3>
            <hr>

        </div>
    </div>


    <div class="row">
        <div class="col-12 ">
            <form class="row" method="post" autocomplete="off">
                <div class="col-12 col-sm-6 mb-3">
                    <div class="mb-3">
                        <label for="floatingInput" class="form-label">Telegram Bot API Token</label>
                        <input type="text" style="-webkit-text-security: disc;" autocomplete="off" name="<?=Config::TELEGRAM_API_TOKEN?>" class="form-control" id="floatingInput" placeholder="777253030:AAFAwetR6k8ZmcW6ij95ab0pg2zstU4-X4w" value="<?=$telegram_api_token;?>">
                    </div>

                    <div class="mb-3">
                        <label for="welcome-message" class="form-label"><?=__('Welcome message', 'bots_master')?></label>

                        <textarea class="form-control" id="welcome-message" name="<?=Config::WELCOME_MESSAGE?>" ><?=$welcome_message?></textarea>

                        <?php wx_includeTpl('admin/parts/message_editor_js', ['textarea_id' => 'welcome-message']); ?>

                    </div>

                    <div class="mb-3">
                        <label for="unknown-message" class="form-label"><?=__('What your bot has to response, if someone sends unexpected message or request?', 'bots_master')?></label>

                        <textarea class="form-control" id="unknown-message" name="<?=Config::UNKNOWN_MESSAGE?>" ><?=$unknown_message?></textarea>

                        <?php wx_includeTpl('admin/parts/message_editor_js', ['textarea_id' => 'unknown-message']); ?>
                    </div>

                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="<?=__('Save Changes')?>">
                    </p>
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    <?=__('<h6>Checklist to get Telegram Bot API Token</h6>
                    <ol>
                        <li>Follow the link <a href="https://t.me/BotFather" target="_blank">https://t.me/BotFather</a></li>
                        <li>Create a bot <a href="https://youtu.be/aNmRNjME6mE?t=72" target="_blank">https://youtu.be/aNmRNjME6mE?t=72</a></li>
                        <li>Copy your API token <a href="https://imgur.com/CpWjw9w" target="_blank">https://imgur.com/CpWjw9w</a></li>
                    </ol>', 'bots_master')?>
                </div>

            </form>
        </div>
    </div>
</div>
