<?php
use phpformbuilder\Form;
use phpformbuilder\Validator\Validator;

/* =============================================
    start session and include form class
============================================= */

session_start();
include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/phpformbuilder/Form.php';

/* =============================================
    validation if posted
============================================= */

if ($_SERVER["REQUEST_METHOD"] == "POST" && Form::testToken('fg-form') === true) {
    // create validator & auto-validate required fields
    include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/phpformbuilder/Validator/Validator.php';
    include_once rtrim($_SERVER['DOCUMENT_ROOT'], DIRECTORY_SEPARATOR) . '/phpformbuilder/Validator/Exception.php';
    $validator = new Validator($_POST);
    $required = array();
    $validator = Form::validate('fg-form');
    
    

    // check for errors
    if ($validator->hasErrors()) {
        $_SESSION['errors']['fg-form'] = $validator->getAllErrors();
    } else {

        //Variablerna för input
        $addPeriod = " Period";
        $mellanslag = ' ';
        $beskrivning = $_POST["beskrivning"] . ' '. $_POST["period"] . ' Period';
        $period = $_POST["period"];
        $antal = $_POST["antal"];
        $apris = $_POST["apris"];
        $intäktskonto = $_POST["intäktskonto"];
        $kundnamn = $_POST["kundnamn"];
        $faktureringadress = $_POST["faktureringadress"];
        $kontaktkod = $_POST["kontaktkod"];
        $referens = $_POST["referens"];
        //$epost = $_POST["epost"];
        $kstkod = $_POST["kstkod"];
        $objkod = $_POST["objkod"];
        $vhdkod = $_POST["vhdkod"];
    $currentDate = new DateTime();
    $dateString = $currentDate->format('Y-m-d');
        //$antal2 = $_POST["antal-2"];
       // $antal3 = $_POST["antal-3"];
        // First check to see if member is set and is a valid array
        //$antalrader = $_POST['job-count'];
        $antalJobb = $_POST['job-count'];
        $antalJobbCast = intval($antalJobb);

        $try = $_POST['job-count'];
        if($try > 1)
        {
        for($i = 2; $i <= $try; $i++)
        {
            ${"antal" . $i} = $_POST['antal-' . $i];
            $list = array(
            array("beskrivning" => $beskrivning, 'period' => $period, 'antal' => ${"antal" . $i}, 'apris' => $apris, 'intäktskonto' => $intäktskonto, 'kundnamn' => $kundnamn, 'faktureringadress' => $faktureringadress, 'kontaktkod' => $kontaktkod, 'referens' => $referens, 'kstkod' => $kstkod, 'objkod' => $objkod, 'vhdkod' => $vhdkod, 'date' => $dateString 
            ),
        );
        $fp = fopen('fakturor.csv', 'a+');
        foreach($list as $fields)
{
fputcsv($fp, $fields);
}


fclose($fp);
        }
    }
        
        

/*$try = $_POST['job-count'];

    for($i = 1; $i <= $antalJobbCast; $i++)
    {
        ${"antal" . $i} = "some value";
        echo "hej";
        //$num = 'num' . $i;
        //echo ${$num} . "<br>";
        $antal = 'antal' . $i;
        //echo ${'antal' . $i} = $i;
        ${$antal} . "<br>";
        echo ${$antal} . "<br>";
        echo ${$antalJobbCast};
        //$antal[i] = $_POST["antal-". [i]];
        //$antal[i] = $_POST["antal-3"];
    }*/
}
            $list = array(
            array("beskrivning" => $beskrivning , 'period' => $period, 'antal' => $antal, 'apris' => $apris, 'intäktskonto' => $intäktskonto, 'kundnamn' => $kundnamn, 'faktureringadress' => $faktureringadress, 'kontaktkod' => $kontaktkod, 'referens' => $referens, 'kstkod' => $kstkod, 'objkod' => $objkod, 'vhdkod' => $vhdkod, 'date' => $dateString  
            ),
        );
        $fp = fopen('fakturor.csv', 'a+');
        foreach($list as $fields)
        {
           fputcsv($fp, $fields);
        }


        fclose($fp);
         //echo array_search(1,$list);
//${"loop" . $x} = "some value";
/*$fp = fopen('fakturor.csv', 'a+');

foreach($list as $fields)
{
fputcsv($fp, $fields);
}


fclose($fp);*/


        // clear the form
        Form::clear('fg-form');
    }

// hidden field value to count posted jobs
if (!isset($_SESSION['fg-form']['job-count'])) {
    $_SESSION['fg-form']['job-count'] = 1;
}
/* ==================================================
    The Form
 ================================================== */

$form = new Form('fg-form', 'horizontal', 'novalidate, data-fv-no-icon=true', 'bs5');
// $form->setMode('development');
$form->addTextarea('beskrivning', '', 'Beskrivning', 'required=required');
$form->addInput('text', 'period', '', 'Period', 'required=required');
$form->addInput('number', 'antal', '', 'Antal', 'required=required');
$form->addBtn('button', 'remove-btn', 0, 'Remove Element', 'class=btn btn-danger remove-element-button, style=visibility:hidden, data-ladda-button=true, data-style=zoom-in', 'add-remove-group');
$form->addBtn('button', 'add-element', 1, 'Add Element', 'class=btn btn-primary ms-2 add-element-button, data-ladda-button=true', 'add-remove-group');
$form->printBtnGroup('add-remove-group');
$form->addHtml('<div id="ajax-elements-container"></div>');
//$addon = '<button class="btn btn-warning" type="button" onclick="document.getElementById(\'input-with-button-after\').value=\'\';">Lägg till ny rad</button>';
//$form->addAddon('input-with-button-after', $addon, 'after');
//$form->addInput('number', 'input-with-button-after', '', 'Antal');
$form->addInput('number', 'apris', '', 'Belopp', 'required=required');
$form->addInput('text', 'kundnamn', '', 'Kundnamn', '');
$form->addInput('text', 'faktureringadress', '', 'Fullständig faktureringsadress', 'required=required');
$form->addInput('text', 'kontaktkod', '', 'Er kontaktkod', '');
$form->addInput('text', 'referens', '', 'Er referens', '');
//$form->addInput('text', 'epost', '', 'Epost PDF', '');
//Lägg till som hidden value? ge ett standardvärde aka tomt värde
$form->addInput('number', 'intäktskonto', '', 'Intäktskonto', 'required=required');
$form->addInput('text', 'kstkod', '', 'KST kod', 'required=required');
$form->addInput('text', 'objkod', '', 'OBJ kod', '');
$form->addInput('text', 'vhdkod', '', 'VHD kod', '');
$form->addBtn('submit', 'button-1', '', 'Skicka faktura', 'class=btn btn-success');
$form->addInput('hidden', 'job-count', '');


/*if($antalJobbCast > 1)
{
    for($i = 1; $i <= $antalJobbCast; $i++)
    {
        echo "hej";
        //$num = 'num' . $i;
        //echo ${$num} . "<br>";
        $antal = 'antal' . $i;
        //echo ${'antal' . $i} = $i;
        ${$antal} . "<br>";
        echo ${$antal} . "<br>";
        echo ${$antalJobbCast};
        //$antal[i] = $_POST["antal-". [i]];
        //$antal[i] = $_POST["antal-3"];
    }
}*/

//$form->addHtml('<div id="ajax-elements-container"></div>');


$form->addPlugin('formvalidation', '#fg-form', 'default', array('language' => 'en_US'));
$form->addPlugin('ajax-data-loader', '#fg-form');

//$form->groupElements('job-1', 'person-1');
/*if('job-index' > 1)
{
    for($i = 1; $i <= 'job-index'; $i++)
    {
    $form->addInput('number', 'antal[i]', '', 'Antal[i]', '');
    echo $antal[i];
    }
}*/
//$form->addSelect('job-1', 'Antal', 'data-slimselect=true, class=job, title=Select a Job ..., required');
/*$form->addOption('person-1', 'Adam Bryant', 'Adam Bryant');
$form->addOption('person-1', 'Lillian Riley', 'Lillian Riley');
$form->addOption('person-1', 'Paula Day', 'Paula Day');
$form->addOption('person-1', 'Kelly Stephens', 'Kelly Stephens');
$form->addOption('person-1', 'Russell Hawkins', 'Russell Hawkins');
$form->addOption('person-1', 'Carl Watson', 'Carl Watson');
$form->addOption('person-1', 'Judith White', 'Judith White');
$form->addOption('person-1', 'Tina Cook', 'Tina Cook');
$form->addSelect('person-1', 'Person 1', 'data-slimselect=true, class=person, title=Select a Person ..., required');*/


?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Fakturaformulär</title>
    <meta name="description" content="">

    <!-- Bootstrap 5 CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- font-awesome -->
    
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <?php $form->printIncludes('css'); ?>
</head>

<body>

    <h1 class="text-center">??</h1>

    <div class="container">

        <div class="row justify-content-center">

            <div class="col-md-11 col-lg-10">
                <?php
                $form->render();
                ?>

            </div>
        </div>
    </div>

    <!-- Bootstrap 5 JavaScript -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <?php
    $form->printIncludes('js');
    $form->printJsCode();
    ?>
<script>
        var addElement = function() {

            // fetch the new elements
            let data = {
                'job-index': parseInt(document.querySelector('input[name="job-count"]').value) + 1
            };
            fetch('ajax-elements.php', {
                method: 'post',
                body: new URLSearchParams(data).toString(),
                headers: {
                    'Content-type': 'application/x-www-form-urlencoded; charset=utf-8'
                },
                cache: 'no-store',
                credentials: 'include'
            }).then(function(response) {
                return response.text()
            }).then(function(data) {
                loadData(data, '#ajax-elements-container', true).then(() => {
                    setTimeout(() => {
                        Ladda.stopAll();

                        // increment job-count
                        var newIndex = parseInt(document.querySelector('input[name="job-count"]').value) + 1;
                        document.querySelector('input[name="job-count"]').value = newIndex;

                        // enable validator for the new fields
                        var form = forms['fg-form'];
                        form.fv.addField(
                            'job-' + newIndex, {
                                validators: {
                                    notEmpty: {}
                                }
                            }
                        );
                        form.fv.addField(
                            'person-' + newIndex, {
                                validators: {
                                    notEmpty: {}
                                }
                            }
                        );

                        // trigger each element on page load
                        if (newIndex < <?php echo $_SESSION['fg-form']['job-count']; ?>) {
                            addElement();
                        }

                        // show remove button if more than 1 job selector
                        if (parseInt(document.querySelectorAll('select.job').length) > 1) {
                            document.querySelector('.remove-element-button').style.visibility = 'visible';
                        }
                    }, 200);
                });
            }).catch(function(error) {
                console.log(error);
            });
        };
        document.addEventListener('DOMContentLoaded', function(event) {
            document.querySelector('.add-element-button').addEventListener('click', addElement);
            document.querySelector('.remove-element-button').addEventListener('click', function() {
                let nodes = document.querySelectorAll('#ajax-elements-container .row');
                let last = nodes[nodes.length - 1];
                last.parentNode.removeChild(last);

                // decrement job-count
                document.querySelector('input[name="job-count"]').value = parseInt(document.querySelector('input[name="job-count"]').value) - 1;

                // hide remove button if only 1 job selector
                if (parseInt(document.querySelectorAll('select.job').length) < 2) {
                    document.querySelector('.remove-element-button').style.visibility = 'hidden';
                }

                // Ajax call to unset removed fields from form required fields
                let data = {
                    'job-index': parseInt(document.querySelector('input[name="job-count"]').value) + 1
                };
                fetch('unset-ajax-elements.php', {
                    method: 'post',
                    body: new URLSearchParams(data).toString(),
                    headers: {
                        'Content-type': 'application/x-www-form-urlencoded; charset=utf-8'
                    },
                    cache: 'no-store',
                    credentials: 'include'
                }).then(function(response) {
                    return response.text()
                }).then(function(data) {
                    Ladda.stopAll();
                    // remove validator for the removed fields
                    let newIndex = parseInt(document.querySelector('input[name="job-count"]').value) + 1;

                    var form = forms['fg-form'];

                    form.fv.removeField('job-' + newIndex);
                    //form.fv.removeField('person-' + newIndex);
                }).catch(function(error) {
                    console.log(error);
                });
            });
            <?php if ($_SESSION['fg-form']['job-count'] > 1) 
            { 

                //$nr = job-count.value
                /*{
                    for($i = 1; $i <= 'job-count'; $i++)
                    {
                    $form->addInput('number', 'antal[i]', '', 'Antal[i]', '');
                    echo $antal[i];
                    }
                }*/
                ?>
                document.querySelector('input[name="job-count"]').value = 1;
                addElement();
            <?php } ?>
        });
    </script>
</body>

</html>
