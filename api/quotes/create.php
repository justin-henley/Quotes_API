<?php
// Headers
header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type,Access-Control-Allow-Methods, Authorization, X-Requested-With');

include_once '../../config/Database.php';
include_once '../../models/Quote.php';
include_once '../../models/Author.php';
include_once '../../models/Category.php';


// Instantiate new DB and connect
$database = new Database();
$connection = $database->connect();

// Instantiate quote object
$quote = new Quote($connection);

// Get raw POST data
$data = json_decode(file_get_contents("php://input"));

// Get quote name from data
$quote->quote = $data->quote;
$quote->authorId = $data->authorId;
$quote->categoryId = $data->categoryId;

// TODO when to check if the table has that author and id? Should fail to create with a message

/* // Attempt to create
if (empty($quote->quote) || empty($quote->authorId) || empty($quote->categoryId)) {
    // Missing parameters
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
} else if ($quote->create()) {
    // TODO must display the names not ids for author and category. You'll need another request
    // Create operation successful
    echo json_encode([
        'id' => $connection->lastInsertId(),
        'quote' => $quote->quote,
        'authorId' => $quote->authorId,
        'categoryId' => $quote->categoryId,
    ]);
} else {
    // Create operation failed
    echo json_encode([
        'message' => 'Quote Not Created'
    ]);
} */

// TODO alt

// Attempt to create


if (empty($quote->quote) || empty($quote->authorId) || empty($quote->categoryId)) {
    // Missing parameters
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
} else {
    // All parameters provided, but not guaranteed to exist in DB
    if (!authorExists($quote->authorId, $connection)) {
        // Author not found
        echo json_encode([
            'message' => 'authorId Not Found'
        ]);
    } else if (!categoryExists($quote->categoryId, $connection)) {
        // Category not found
        echo json_encode([
            'message' => 'categoryId Not Found'
        ]);
    } else if ($quote->create()) {
        // Category and Author both exist
        // Create operation successful
        echo json_encode([
            'id' => $connection->lastInsertId(),
            'quote' => $quote->quote,
            'authorId' => $quote->authorId,
            'categoryId' => $quote->categoryId,
        ]);
    } else {
        // Create operation failed
        echo json_encode([
            'message' => 'Quote Not Created'
        ]);
    }
}


/**
 * Function to check if an authorId exists in the database
 * 
 * @param {int} authorId - The author ID to check
 * @param {PDO} connection - An existing PDO database connection
 * @return {boolean} - True if author exists in database
 */

function authorExists($authorId, $connection)
{
    // TODO
    return false;
}

/**
 * Function to check if an categoryId exists in the database
 * 
 * @param {int} categoryId - The author ID to check
 * @param {PDO} connection - An existing PDO database connection
 * @return {boolean} - True if author exists in database
 */

function categoryExists($categoryId, $connection)
{
    // TODO
    return false;
}
