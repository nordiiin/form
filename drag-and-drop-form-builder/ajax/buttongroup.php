<?php

use phpformbuilder\Form;

session_start();
include_once '../../phpformbuilder/Form.php';
$json = json_decode($_POST['data']);
foreach ($json as $var => $val) {
    ${$var} = $val;
}

$form = new Form('fg-element', 'horizontal');

foreach ($buttons as $btn) {
    if (!empty($btn->icon)) {
        $icon_html = '<i class="' . $icon . '" aria-label="hidden"></i>';
        if (strpos($icon, 'material') > -1) {
            $ic = explode(' ', $icon);
            $icon_class = $ic[0];
            $icon_text = $ic[1];
            $icon_html = '<i class="' . $icon_class . '" aria-label="hidden">' . $icon_text . '</i>';
        }
        if ($btn->iconPosition === 'before') {
            $btn->label = $icon_html . ' ' . $btn->label;
        } else {
            $btn->label .= ' ' . $icon_html;
        }
    }
    $form->addBtn($btn->type, $btn->name, $btn->value, $btn->label, 'class=' . $btn->clazz, $name);
}
if ($center === 'true') {
    $form->centerContent();
}
$form->printBtnGroup($name);
echo $form->html;
