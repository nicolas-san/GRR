<?php
use Symfony\Component\EventDispatcher\Event;

global $dispatcher;

$dispatcher->addListener('editentry.form_before', function (Event $event) {
    echo "<br>Dans le listener avant l'affichage du form<br>";
});