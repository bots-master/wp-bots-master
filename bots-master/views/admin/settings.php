<?php

use WebXID\BotsMaster\Config;
use function WebXID\BotsMaster\includeTpl;
use WebXID\BotsMaster\Tpl;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

includeTpl('admin/parts/header', [
    'errors' => $wx_errors ?? null,
    'messages' => $wx_messages ?? null
]);

wp_enqueue_script('ckeditor', Tpl::pluginUrl( 'assets/admin/js/ckeditor5.min.js'));

?>
<div id="bots-master" class="container float-start">
    <div class="row mb-3">
        <div class="col-12">
            <h3><?php echo get_admin_page_title()?></h3>
            <hr>

        </div>
    </div>


    <div class="row">
        <div class="col-12 ">
            <form class="row" method="post" autocomplete="off">
                <div class="col-12 col-sm-6 mb-3">
                    <div class="mb-3">
                        <label for="floatingInput" class="form-label">Telegram Bot API Token</label>
                        <input type="text" style="-webkit-text-security: disc;" autocomplete="off" name="<?php echo Config::TELEGRAM_API_TOKEN?>" class="form-control" id="floatingInput" placeholder="777253030:AA************************zstU4-X4w" value="<?php echo esc_textarea($telegram_api_token)?>">
                    </div>

                    <div class="mb-3">
                        <label for="welcome-message" class="form-label"><?php echo __('Welcome message', 'bots_master');?></label>

                        <textarea class="form-control wx-bots-master-ckeditor" id="welcome-message" name="<?php echo Config::WELCOME_MESSAGE;?>" ><?php echo esc_html($welcome_message)?></textarea>

                    </div>

                    <div class="mb-3">
                        <label for="unknown-message" class="form-label"><?php echo __('What your bot has to response, if someone sends unexpected message or request?', 'bots_master')?></label>

                        <textarea class="form-control wx-bots-master-ckeditor" id="unknown-message" name="<?php echo Config::UNKNOWN_MESSAGE?>" ><?php echo esc_html($unknown_message)?></textarea>
                    </div>

                    <p class="submit">
                        <input type="submit" name="submit" id="submit" class="button button-primary" value="<?php echo __('Save Changes')?>">
                    </p>
                </div>

                <div class="col-12 col-sm-6 mb-3">
                    <?php echo __('<h6>Checklist to get Telegram Bot API Token</h6>
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
