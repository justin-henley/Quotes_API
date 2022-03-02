<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Category.php';

// Instantiate new DB and connect
$database = new Database();
$connection = $database->connect();

// Instantiate Category object
$category = new Category($connection);

// Read all category entries from database
$result = $category->read();

// Check if any category results were returned
if ($result->rowCount() > 0) {
    // Create an array to store categories data
    $catArr = [];
    $catArr['data'] = [];

    // Iterate over the rows
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $catEntry = [
            'id' => $id,
            'category' => $category,
        ];

        // Push category entry to categories array
        array_push($catArr['data'], $catEntry);
    }

    // Turn to JSON and output
    print_r(json_encode($catArr));
} else {
    // No categories found 
    echo json_encode(['message' => 'No Categories Found']);
}
