<?php

use phpformbuilder\Form;

session_start();
include_once '../../phpformbuilder/Form.php';
$json = json_decode($_POST['data']);

foreach ($json as $var => $val) {
    ${$var} = $val;
}

// convert boolean string to boolean
$inline = filter_var($inline, FILTER_VALIDATE_BOOLEAN);

$form = new Form('fg-element', 'horizontal');
if (!empty($helper)) {
    $form->addHelper($helper, $name);
}
if (empty($label)) {
    $form->setCols(0, 12);
}
foreach ($radioButtons as $rad) {
    $rad_attr = array();
    if ($rad->value === $value) {
        $rad_attr[] = 'checked';
    }
    if (isset($rad->disabled) && $rad->disabled === 'true') {
        $rad_attr[] = 'disabled';
    }
    $rad_attr = implode(', ', $rad_attr);
    $form->addRadio($name, $rad->text, $rad->value, $rad_attr);
}
$form->printRadioGroup($name, $label, $inline, $attr);
echo $form->html;
