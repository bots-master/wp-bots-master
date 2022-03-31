<?php

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
            <h3><?php echo get_admin_page_title(); ?></h3>
            <hr>

        </div>
    </div>


    <div class="row">
        <div class="col-12 ">
            <form class="row" method="post">
                <div class="col-12 mb-3">
                    <textarea class="wx-bots-master-ckeditor" name="message" id="message-editor" cols="30" rows="10" maxlength="4000"></textarea>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary"><?php echo __('Send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>
