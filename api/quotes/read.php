<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';

// Instantiate new DB and connect
$database = new Database();
$connection = $database->connect();

// Instantiate quote object
$quote = new Quote($connection);

// Read all quote entries from database
$result = $quote->read();

// TODO error handling and messages
// TODO allow for user providing a category id, author id, or both

// Check if any quote results were returned
if ($result->rowCount() > 0) {
    // Create an array to store quotes data
    $quoteArr = [];

    // Iterate over the rows
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        extract($row);

        $quoteEntry = [
            'id' => $id,
            'quote' => $quote,
            'authorId' => $authorId,
            'categoryId' => $categoryId,
        ];

        // Push category entry to quotes array
        array_push($quoteArr, $quoteEntry);
    }

    // Turn to JSON and output
    echo json_encode($quoteArr);
} else {
    // No quotes found 
    echo json_encode(['message' => 'No Quotes Found']);
}
