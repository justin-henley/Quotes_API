<?php
class Category
{
    // DB properties
    private $conn;
    private $table = 'categories';

    // Properties
    public $id;
    public $category;

    // Constructor 
    // param: {PDO} dbConn - An active database connection
    public function __construct($dbConn)
    {
        $this->conn = $dbConn;
    }

    // Read all categories
    public function read()
    {
        // TODO
    }

    // Read a single category by id
    public function readSingle()
    {
        // TODO
    }

    // Create a new category
    public function create()
    {
        // TODO
    }

    // Update an existing category
    public function update()
    {
        // TODO
    }

    // Delete Category
    public function delete()
    {
        // TODO
    }
}
