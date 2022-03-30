<?php

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
            <form class="row" method="post">
                <div class="col-12 mb-3">
                    <textarea name="message" id="message-editor" cols="30" rows="10" maxlength="4000"></textarea>
                </div>
                <div class="col-12 text-end">
                    <button type="submit" class="btn btn-primary"><?=__('Send')?></button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php wx_includeTpl('admin/parts/message_editor_js', ['textarea_id' => 'message-editor']); ?>
