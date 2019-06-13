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
    //Display a view-set view as new template and echo out the view
    $view = new Template();
    echo $view->render('views/home.html');
});

$f3->route('GET|POST /order', function($f3) {
    $_SESSION = [];
    if (isset($_POST))
    {
        $animal = $_POST['animal'];
        $qty = $_POST['qty'];

        if (validString($animal) && validQty($qty))
        {
            $_SESSION['animal'] = $animal;
            $_SESSION['qty'] = $qty;
            $f3->reroute('/order2');
        }
        else
        {
            $f3->set("errors['animal']", 'Please enter a valid animal.');
            $f3->set("errors['qty']", 'Please enter a valid quantity.');
        }
    }
    $view = new Template();
    echo $view->render('views/form1.html');
});

$f3->route('GET|POST /order2', function($f3) {

    if (isset($_POST['color']))
    {
        $color = $_POST['color'];
        if (validColor($color))
        {
            $_SESSION['color'] = $color;
            $f3->reroute('/results');
        }
        else
        {
            $f3->set("errors['color']", 'Please enter a valid color.');
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
