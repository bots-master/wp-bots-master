<?php

use WebXID\EDMo\Validation\Error;
use WebXID\BotsMaster\Tpl;

/** @var Error $errors */
$errors = ($errors ?? null) instanceof Error
    ? $errors->toArray()
    : []
;

/** @var Error $messages */
$messages = ($messages ?? null) instanceof Error
    ? $messages->toArray()
    : []
;

wp_enqueue_style('bootstrap', Tpl::pluginUrl('assets/admin/css/bootstrap.min.css'));
wp_enqueue_style('bots_master', Tpl::pluginUrl('assets/admin/css/style.css'));

wp_enqueue_script('alpine', Tpl::pluginUrl('assets/admin/js/alpine.3.9.5.min.js'));
wp_enqueue_script('bootstrap', Tpl::pluginUrl('assets/admin/js/bootstrap.min.js'));

?>

<div class="container mt-4 float-start <?php echo ($messages ? 'mb-5' : '')?>">

    <?php

    foreach ($messages as $param_name => $error_message) {
        echo '
            <div class="alert alert-success alert-dismissible fade show" role="alert">
              ' . $error_message . '
              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        ';
    }

    ?>

</div>



<div class="container mt-4 float-start <?php echo ($errors ? 'mb-5' : '')?>">

<?php

foreach ($errors as $param_name => $error_message) {
    echo '
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          ' . $error_message . '
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    ';
}

?>

</div>
