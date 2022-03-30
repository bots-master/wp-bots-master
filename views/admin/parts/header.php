<?php
use WebXID\EDMo\Validation\Error;

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

?>
<link href="https://fonts.googleapis.com/css2?family=Material+Icons" rel="stylesheet">
<link rel="stylesheet" href="<?=plugins_url( 'bot-master/assets/admin/css/bootstrap.min.css' );?>">
<link rel="stylesheet" href="<?=plugins_url( 'bot-master/assets/admin/css/style.css' );?>">

<script src="//unpkg.com/alpinejs" defer></script>
<script src="<?=plugins_url( 'bot-master/assets/admin/js/bootstrap.min.js' );?>"></script>
<script src="<?=plugins_url( 'bot-master/assets/admin/js/ckeditor/ckeditor.js' );?>"></script>


<div class="container mt-4 float-start <?=($messages ? 'mb-5' : '')?>">

    <?php

    foreach ($messages as $param_name => $error_message) {
        echo <<<TPL
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          {$error_message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    TPL;
    }

    ?>

</div>



<div class="container mt-4 float-start <?=($errors ? 'mb-5' : '')?>">

<?php

foreach ($errors as $param_name => $error_message) {
    echo <<<TPL
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
          {$error_message}
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    TPL;
}

?>

</div>
