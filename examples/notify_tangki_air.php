<?php

// Include core/telegram.php
    require_once "../core/telegram.php";

$tgbot = new TelegramAPI();

// what is your telegram bot token?
    $tgbot->setRobot('12342:dYhv6FZJNguuuAKG');

// what is user id to send this message?
    $tgbot->setChatId('58594');

// solar voltage @ tangki air logics
    $voltage = 0; // Input: value from DB or PHP Input
    $voltage = $v / 10;

    $condition = '';
    if($voltage <= 10.5){
        $condition = 'BAD! Need to replace';
    }
    elseif($voltage >= 11.2){
        $condition = 'Good!';
    }
    else{
        $condition = 'Alert! Battery is now in mid-level';
    }

    $message = "### Tangki PTP.TA.1 ###\n\n";
    $message .= "Reported at: " . gmdate('d/m/Y h:i A', time() + (3600*8)) . "\n";
    $message .= "Voltage: " . $voltage . " V\n";
    $message .= "Status: " . $condition;

// send message now!
    $tg->send('sendMessage', $message);

