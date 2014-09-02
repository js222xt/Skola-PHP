<?php

require_once("view/HTMLPage.php");
require_once("view/LoginView.php");
require_once("model/LoginModel.php");
require_once("model/Logg.php");

$loginView = new \view\LoginView();

session_start();

$html = $loginView->InitializeForm();
$html .= $loginView->GetDateHTML();
$loggedStatus = $loginView->GetLoggedInStatus();
$title = $loginView->GetTitle();


//HTML output
$pageView = new \view\HTMLPage();
echo $pageView->getPageHTML($title, "<h1>Laborationskod js222xt</h1> <h2>" . $loggedStatus . "</h2>\n $html");
