<?php

use function WebXID\BotsMaster\includeTpl;

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

includeTpl('admin/parts/header', [
    'errors' => $wx_errors ?? null,
    'messages' => $wx_messages ?? null
]);

?>
<div id="bots-master" class="container float-start">
    <div class="row mb-3">
        <div class="col-12">
            <h3><?php echo get_admin_page_title(); ?></h3>
            <p>Here is a list of Telegram Channels and Group chats</p>
            <hr>

        </div>
    </div>


    <div class="row"
         x-data='{
            items: <?php echo json_encode($chats_list)?>,
            count: <?php echo count($chats_list)?>
         }'>
        <div class="col-12 col-md-6 ">
            <form class="row" method="post">
                <div class="col-12 mb-3">

                    <template x-for="(item, index) in items" :key="index">

                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">@</span>
                            <input
                                x-model="item"
                                name="chats[]"
                                type="text" class="form-control" placeholder="Username" aria-label="Username" aria-describedby="basic-addon1">
                            <button
                                @click="items = items.filter((val, ind) => ind !== index)"
                                class="btn btn-outline-danger" type="button" id="button-addon2"><span class="material-icons">highlight_off</span></button>
                        </div>

                    </template>


                </div>
                <div class="col-12">
                    <input type="submit" class="btn btn-primary" value="<?php echo __('Save')?>" name="submit">
                    <button type="button" @click="items.push('');" class="btn btn-outline-secondary float-end">+ <?php echo __('Add Chat', 'bots_master')?></button>
                </div>
            </form>
        </div>
        <div class="col-12 col-md-1 "></div>
        <div class="col-12 col-md-5 ">
        <?php echo __('<h6>Checklist to make it works</h6>
            <ol>
                <li>Before starting, you should setup a bot at Bot Settings page</li>
                <li>
                    Add your bot to a Group Chat or to admins list into a Telegram Channel - it has to be able <a
                        href="https://imgur.com/ubjcPFu" target="_blank">to post messages</a>
                </li>
                <li>Copy and past the chat username to a field on the left side</li>
                <li>Press the Save button</li>
            </ol>

            Now you are able to send messages at Send Message page to added Group Chat / Channel')
        ?>
        </div>
    </div>
</div>

<?php includeTpl('admin/parts/message_editor_js', ['textarea_id' => 'message-editor'])?>
