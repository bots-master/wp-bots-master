<?php

namespace WebXID\BotsMaster\Controllers\Admin;

interface AdminControllerInterface
{
    public function loadPage();

    public function postRequest();

    public function getRequest();
}
