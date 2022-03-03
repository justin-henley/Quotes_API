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

// Get ID from URL
$quote->id = isset($_GET['id'])
    ? $_GET['id']
    : null;

// TODO this is ugly
// Return early if parameters missing;
if (empty($quote->id)) {
    echo json_encode([
        'message' => 'Missing Required Parameters'
    ]);
    return;
}

// Get quote data
$quote->readSingle();

// Check if a quote was returned for the specified ID (quote exists in database)
if ($quote->quote) {
    // Read operation found a quote
    // Create results array
    $quoteArr = [
        'id' => $quote->id,
        'quote' => $quote->quote,
        'authorId' => $quote->authorId,
        'categoryId' => $quote->categoryId,
    ];

    // Convert to JSON and output 
    echo (json_encode($quoteArr));
} else {
    // Read operation did not find a quote
    echo json_encode([
        'message' => 'No Quotes Found'
    ]);
}
