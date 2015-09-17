<?php

use Grr\Event\EditEntryForm;

//global $dispatcher;

$dispatcher->addListener('editentry.form_before', function (EditEntryForm $event) {
    echo "<br>Dans le listener avant l'affichage du form<br>";
    echo $event->getTest();
});