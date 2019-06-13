<?php
session_start();
//Turn on error reporting
ini_set('display_errors', 1);
error_reporting(E_ALL);

//require the autoload file autoload.php
require_once('vendor/autoload.php');
require_once('model/validate-functions.php');

//Create an instance of the Base class/ instantiate Fat-Free
$f3 = Base::instance();

//Turn on Fat-free error reporting/Debugging
$f3->set('DEBUG',3);
$f3->set('colors', ['pink', 'green', 'blue']);
$f3->set('fur', ['hairless', 'short', 'medium', 'long', 'extra shaggy']);


//Define a default route (use backlash / )
$f3->route('GET /', function()
{
    // empty the session array
    $_SESSION = [];

    //Display a view-set view as new template and echo out the view
    $view = new Template();
    echo $view->render('views/home.html');
});

$f3->route('GET|POST /order', function($f3) {

    if (!empty($_POST))
    {
        $animal = $_POST['animal'];
        $qty = $_POST['qty'];
        $valid = true;

        if (validString($animal))
        {
            $_SESSION['animal'] = $animal;
        }
        else
        {
            $f3->set("errors['animal']", 'Please enter a valid animal.');
            $valid = false;
        }

        if (validQty($qty))
        {
            $_SESSION['qty'] = $qty;
        }
        else
        {
            $f3->set("errors['qty']", 'Please enter a valid quantity.');
            $valid = false;
        }

        if ($valid)
        {
            $f3->reroute('/order2');
        }
    }
    $view = new Template();
    echo $view->render('views/form1.html');
});

$f3->route('GET|POST /order2', function($f3) {

    if (!empty($_POST))
    {
        $color = $_POST['color'];
        $fur = $_POST['fur'];
        $valid = true;

        if (validColor($color))
        {
            $_SESSION['color'] = $color;
        }
        else
        {
            $f3->set("errors['color']", 'Please enter a valid color.');
            $valid = false;
        }

        if (validFur($fur))
        {
            $_SESSION['fur'] = $fur;
        }
        else
        {
            $f3->set("errors['fur']", 'Please choose a valid fur type.');
            $valid = false;
        }

        if ($valid)
        {
            $f3->reroute('/results');
        }
    }
    $view = new Template();
    echo $view->render('views/form2.html');
});

$f3->route('GET /results', function() {
    $view = new Template();
    echo $view->render('views/results.html');
});

//Define a route with a parameter
$f3->route('GET /@animal', function($f3, $params) {
    $animal = $params['animal'];

    switch ($animal) {
        case 'dog':
            echo "<h3>Woof!</h3>";
            break;
        case 'cat':
            echo "<h3>Meow</h3>";
            break;
        case 'pig':
            echo "<h3>Oink</h3>";
            break;
        case 'bear':
            echo "<h3>Grrr</h3>";
            break;
        case 'bird':
            echo "<h3>Hello</h3>";
            break;
        default:
            $f3->error(404);
    }
});

//Run fat free F3
$f3->run();
