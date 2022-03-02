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

// Get ID from URL
$author->id = isset($_GET['id'])
    ? $_GET['id']
    : null;

// TODO this is ugly
// Return early if parameters missing;
if (empty($author->id)) {
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
    return;
}

// Get author data
$author->readSingle();

// Check if a author was returned for the specified ID (author exists in database)
if ($author->author) {
    // Read operation found a author
    // Create results array
    $catArr = [
        'id' => $author->id,
        'author' => $author->author,
    ];

    // Convert to JSON and output 
    echo (json_encode($catArr));
} else {
    // Read operation did not find a author
    echo json_encode([
        'message' => 'authorId Not Found'
    ]);
}
