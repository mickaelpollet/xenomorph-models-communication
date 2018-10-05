<?php

// Déclaration de l'autoload
require __DIR__ . '/vendor/autoload.php';

echo "<h1>XENOMORPH - Models : Communication</h1>";

$TestUser = array();
$TestUser['author'] = 'pollet.m@mipih.fr';
$TestUser['subject'] = 'test subject';
$TestUser['body'] = '<p>test body</p>';
$TestUser['signatory'] = 'mickaelpollet@gmail.com';

$TestMailer = new XMailer($TestUser);

//$TestMailer->addSignatory('mickaelpollet@gmail.com');

//$TestMailer->send();

var_dump($TestMailer);

?>
