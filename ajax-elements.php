<?php

use phpformbuilder\Form;

session_start();

if (!isset($_POST['job-index']) || !is_numeric($_POST['job-index'])) {
    exit();
}

include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/phpformbuilder/Form.php';

$index = $_POST['job-index'];

$form = new Form('fg-form', 'horizontal', 'novalidate', 'bs5');
$form->setMode('development');
//$form->setCols(2, 4, 'md');
//$form->groupElements('job-' . $index);
$form->addInput('job-' . $index, 'antal-' . $index, '', 'Antal', '');
//$form->addInput('number', 'antal', '', 'Antal', 'required=required');
//$form->addOption('job-' . $index, 'Content writer', 'Content writer');
//$form->addSelect('job-' . $index, 'Job ' . $index, 'data-slimselect=true, class=job, title=Select a Job ..., required');
//$form->addOption('person-' . $index, 'Adam Bryant', 'Adam Bryant');


/* render select lists */

echo $form->html;

// The script below updates the form token value with the new generated token
?>
<script>
    document.querySelector('input[name="fg-form-token"]').value = '<?php echo $_SESSION['fg-form_token']; ?>';

    /* // enable the slimselect plugin for the new fields
    new SlimSelect({
        select: 'select[name="<?php echo 'job-' . $index; ?>"]'
    }); */
    /* new SlimSelect({
        select: 'select[name="<?php echo 'person-' . $index; ?>"]'
    });*/
</script>
