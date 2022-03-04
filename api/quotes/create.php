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

// Attempt to create the quote
// Check all parameters are non-null, and category and author exist in the database

if (empty($quote->quote) || empty($quote->authorId) || empty($quote->categoryId)) {
    // Missing parameters
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
} else if (!authorExists($quote->authorId, $connection)) {
    // Author not found in database
    echo json_encode([
        'message' => 'authorId Not Found'
    ]);
} else if (!categoryExists($quote->categoryId, $connection)) {
    // Category not found in database
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



/**
 * Function to check if an authorId exists in the database
 * 
 * @param {int} authorId - The author ID to check
 * @param {PDO} connection - An existing PDO database connection
 * @return {boolean} - True if author exists in database
 */
function authorExists($authorId, $connection)
{
    // Create an author object
    $author = new Author($connection);
    $author->id = $authorId;

    // Attempt to read that single author record
    $author->readSingle();

    // If an author name exists in the author object now, the record exists, return true
    if ($author->author) return true;
    else return false;
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
    // Create an category object
    $category = new Category($connection);
    $category->id = $categoryId;

    // Attempt to read that single category record
    $category->readSingle();

    // If an category name exists in the category object now, the record exists, return true
    if ($category->category) return true;
    else return false;
}
