<?php

// Déclaration de l'autoload
require __DIR__ . '/vendor/autoload.php';

echo "<h1>XENOMORPH - Models : Communication</h1>";

$TestUser = array();
$TestUser['author'] = 'pollet.m@mipih.fr';
$TestUser['signatory'] = 'mickaelpollet@gmail.com';
$TestUser['subject'] = 'test subject';
$TestUser['body'] = 'test body';

var_dump($TestUser);

$TestMailer = new XMailer();

$TestMailer->setAuthor($TestUser['author']);
$TestMailer->addSignatory($TestUser['signatory']);
$TestMailer->setSubject($TestUser['subject']);
$TestMailer->setBody($TestUser['body']);


var_dump($TestMailer);


?>
