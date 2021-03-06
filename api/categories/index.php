<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

// Get the request method
$requestMethod = $_SERVER['REQUEST_METHOD'];

// CORS solution
if ($requestMethod === 'OPTIONS') {
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
    header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
}

// Choose the operation to carry out
switch ($requestMethod) {
    case 'GET':
        // If an id was provided, read a single category. Otherwise read all categories
        if (isset($_GET['id'])) {
            require './read_single.php';
        } else {
            require './read.php';
        }
        break;
    case 'POST':
        require './create.php';
        break;
    case 'PUT':
        require './update.php';
        break;
    case 'DELETE':
        require './delete.php';
        break;
}
