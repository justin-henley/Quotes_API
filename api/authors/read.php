<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Author.php';

// Instantiate new DB and connect
$database = new Database();
$connection = $database->connect();

// Instantiate author object
$author = new Author($connection);

// Read all author entries from database
$result = $author->read();

// TODO error handling and messages

// Check if any author results were returned
if ($result->rowCount() > 0) {
    // Create an array to store authors data
    $authorArr = [];
    $authorArr['data'] = [];

    // Iterate over the rows
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $authorEntry = [
            'id' => $id,
            'author' => $author,
        ];

        // Push author entry to authors array
        array_push($authorArr['data'], $authorEntry);
    }

    // Turn to JSON and output
    echo json_encode($authorArr);
} else {
    // No authors found 
    echo json_encode(['message' => 'No Authors Found']);
}
