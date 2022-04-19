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
foreach ($checkboxes as $chk) {
    $chk_attr = array();
    if ($chk->value === $value) {
        $chk_attr[] = 'checked';
    }
    if (isset($chk->disabled) && $chk->disabled === 'true') {
        $chk_attr[] = 'disabled';
    }
    $chk_attr = implode(', ', $chk_attr);
    $form->addCheckbox($name, $chk->text, $chk->value, $chk_attr);
}
$form->printCheckboxGroup($name, $label, $inline, $attr);
echo $form->html;
