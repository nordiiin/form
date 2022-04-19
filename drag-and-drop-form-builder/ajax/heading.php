<?php

use phpformbuilder\Form;

session_start();
include_once '../../phpformbuilder/Form.php';
$json = json_decode($_POST['data']);

foreach ($json as $var => $val) {
    ${$var} = $val;
}

$form = new Form('fg-element', 'horizontal');
$form->addHeading($value, $type, 'class=' . $clazz);
echo $form->html;
