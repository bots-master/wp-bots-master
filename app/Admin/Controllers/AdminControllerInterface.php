<?php

namespace WebXID\BotsMaster\Admin\Controllers;

interface AdminControllerInterface
{
    public function loadPage();

    public function postRequest();

    public function getRequest();
}
