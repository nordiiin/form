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

if ($_SERVER["REQUEST_METHOD"] == "POST" && Form::testToken('data-form')) {
    // create validator & auto-validate required fields
    $validator = Form::validate('data-form');

    // check for errors
    if ($validator->hasErrors()) {
        $_SESSION['errors']['data-form'] = $validator->getAllErrors();
    } else {
        // clear the form
        Form::clear('data-form');
    }
}

/* ==================================================
    The Form
 ================================================== */

$form = new Form('data-form', 'horizontal', 'novalidate, data-fv-no-icon=true', 'bs5');
// $form->setMode('development');
$form->addInput('text', 'datum', '', 'Datum', 'data-litepick=true,data-auto-apply=true,data-delimiter=-,data-dropdown-min-year=2020,data-dropdown-max-year,data-dropdown-months=false,data-dropdown-years=false,data-first-day=1,data-format=YYYYMMDD,data-inline-mode=false,data-lang=en-US,data-max-date,data-max-days,data-min-date,data-min-days,data-number-of-columns=1,data-number-of-months=1,data-reset-button=false,data-single-mode=false,data-split-view=true,data-start-date');
$form->addPlugin('litepicker', '#datum', 'default');
$form->setCols(0, 12);
$form->centerContent();
$form->centerContent();
//$form->addBtn('submit', 'button-3', '', '<i class="fa-solid fa-download" aria-hidden="true"></i> <a  href="datumfaktura.csv" download="datumfaktura" role="button"></a>', 'class=btn btn-primary');
$form->addBtn('submit', 'button-3', '', 'Hämta fakturor', 'class=btn btn-success');
//$form->addBtn('button', 'button-1', '', '<a href="datumfaktura.csv" >Hämta fakturor</a>', 'class=btn btn-primary');

$form->centerContent(false);
$form->addPlugin('formvalidation', '#data-form', 'default', array('language' => 'en_US'));

$datum = $_POST["datum"];
list($one, $two) = explode("-", $datum, 2);
echo $one;
//echo $datum;
// Function to get all the dates in given range
function getDatesFromRange($start, $end, $format = 'Y-m-d') {
	
	//Empty array
	$array = array();
	
	// Variable that store the date interval
	// of period 1 day
	$interval = new DateInterval('P1D');

	$realEnd = new DateTime($end);
	$realEnd->add($interval);

	$period = new DatePeriod(new DateTime($start), $interval, $realEnd);

	// Use loop to store date into array
	foreach($period as $date) {				
		$array[] = $date->format($format);
	}

	// Return array
	return $array;
}

// Function call with passing the start date and end date
$Date = getDatesFromRange($one, $two);

var_dump($Date);

$data = [];
// open the file
$fp = fopen('fakturor.csv', 'r');

/*if ($fp === false) {
	die('Cannot open the file ' . $filename);
}*/

/*if ($order_date_ts >= $date_from_ts && $order_date_ts <= $date_to_ts) {
}*/
while (($row = fgetcsv($fp)) !== false) {
	/*if(strpos($row[12], "2022-04-18") === 0) {
            continue;
        }*/
        if ($row[12] < '2022-04-17')  //Jämför med array? Tar antingen alla eller ingen?
        {
            continue;
        }
	
	$data[] = $row;
	
	
}

fclose($fp);


//print_r($Date);
//print_r($data);



$fp = fopen('datumfaktura.csv', 'w');
// Loop through file pointer and a line
foreach ($data as $fields) {
    fputcsv($fp, $fields);
}
  
fclose($fp);



//echo $datum;
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Php Form Builder - Bootstrap 5 form</title>
    <meta name="description" content="">

        
    <!-- Bootstrap 5 CSS -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <!-- font-awesome -->
    
    <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    
    <?php $form->printIncludes('css'); ?>
</head>

<body>

    <h1 class="text-center">Php Form Builder - Bootstrap 5 form</h1>

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

</body>

</html>