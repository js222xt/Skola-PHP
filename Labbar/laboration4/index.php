<?php

require_once("src/view/HTMLPage.php");
require_once("src/controller/MasterController.php");
require_once("src/model/SessionModel.php");

$session = new \model\SessionModel();
$session->startSession();

$masterController = new \controller\MasterController($session);
$html = $masterController->run();

$pageView = new \view\HTMLPage();
echo $pageView->getPage($html);