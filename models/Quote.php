<?php
class Quote
{
    // DB properties
    private $conn;
    private $table = 'quotes';

    // Properties
    public $id;
    public $quote;
    public $categoryId;
    public $authorId;

    // Constructor 
    // param: {PDO} dbConn - An active database connection
    public function __construct($dbConn)
    {
        $this->conn = $dbConn;
    }

    // Read all quotes
    public function read()
    {
        // Clean data before building the where clause
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));
        $this->authorId = htmlspecialchars(strip_tags($this->authorId));

        // Build a WHERE statement if a category or author id is set
        $where = "";

        if ($this->categoryId || $this->authorId) {
            $args = [];
            if ($this->categoryId) {
                array_push($args, "quotes.categoryId = :categoryId");
            }
            if ($this->authorId) {
                array_push($args, "quotes.authorId = :authorId");
            }
            $where = "WHERE " . implode(" AND ", $args);
        }

        // Create query
        $query =
            "SELECT quotes.id, quotes.quote, quotes.authorId, quotes.categoryId, authors.author, categories.category 
            FROM ((quotes
            INNER JOIN authors ON quotes.authorId = authors.id)
            INNER JOIN categories ON quotes.categoryId = categories.id)
            {$where}
            ORDER BY quotes.id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Bind values
        if ($this->categoryId) $stmt->bindValue(':categoryId', $this->categoryId);
        if ($this->authorId) $stmt->bindValue(':authorId', $this->authorId);

        // Execute the statement
        $stmt->execute();

        return $stmt;
    }

    // Read a single quote by id
    public function readSingle()
    {
        // Create query
        $query =
            "SELECT quotes.id, quotes.quote, quotes.authorId, quotes.categoryId, authors.author, categories.category 
            FROM ((quotes
            INNER JOIN authors ON quotes.authorId = authors.id)
            INNER JOIN categories ON quotes.categoryId = categories.id)
            WHERE
                quotes.id = :id
            LIMIT 0,1";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind ID
        $stmt->bindValue(':id', $this->id);

        // Execute the statement
        $stmt->execute();

        // Return statement
        return $stmt;
    }

    // Create a new quote
    public function create()
    {
        // Create query
        $query =
            "INSERT INTO {$this->table}
            SET
                quote = :quote,
                authorId = :authorId,
                categoryId = :categoryId";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->quote = htmlspecialchars(strip_tags($this->quote), ENT_NOQUOTES);
        $this->authorId = htmlspecialchars(strip_tags($this->authorId));
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));

        // Bind quote
        $stmt->bindValue(':quote', $this->quote);
        $stmt->bindValue(':authorId', $this->authorId);
        $stmt->bindValue(':categoryId', $this->categoryId);

        // Execute the statement
        // If the statement executes and affected a row, return true for success
        return ($stmt->execute());
    }

    // Update an existing quote
    public function update()
    {
        // Create query
        $query =
            "UPDATE {$this->table}
            SET
                quote = :quote,
                authorId = :authorId,
                categoryId = :categoryId
            WHERE
                id = :id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));
        $this->quote = htmlspecialchars(strip_tags($this->quote), ENT_NOQUOTES);
        $this->categoryId = htmlspecialchars(strip_tags($this->categoryId));
        $this->authorId = htmlspecialchars(strip_tags($this->authorId));

        // Bind quote
        $stmt->bindValue(':quote', $this->quote);
        $stmt->bindValue(':authorId', $this->authorId);
        $stmt->bindValue(':categoryId', $this->categoryId);
        $stmt->bindValue(':id', $this->id);

        // Execute the statement and check row count to see if any rows affected
        // If the statement executes and affected a row, return true for success
        return ($stmt->execute() && $stmt->rowCount());
    }

    // Delete quote
    public function delete()
    {
        // Create query
        $query = "DELETE FROM {$this->table} WHERE id = :id";

        // Prepare the statement
        $stmt = $this->conn->prepare($query);

        // Clean data
        $this->id = htmlspecialchars(strip_tags($this->id));

        // Bind id
        $stmt->bindValue(':id', $this->id);

        // Execute the statement and check row count to see if any rows affected
        // If the statement executes and affected a row, return true for success
        return ($stmt->execute() && $stmt->rowCount());
    }

    /**
     * Function to check if an authorId exists in the database
     * 
     * @return {boolean} - True if author exists in database
     */
    public function authorExists()
    {
        include_once '../../models/Author.php';

        // Create an author object
        $author = new Author($this->conn);
        $author->id = $this->authorId;

        // Attempt to read that single author record
        $author->readSingle();

        // If an author name exists in the author object now, the record exists, return true
        if ($author->author) return true;
        else return false;
    }

    /**
     * Function to check if a categoryId exists in the database
     * 
     * @return {boolean} - True if category exists in database
     */
    public function categoryExists()
    {
        include_once '../../models/Category.php';

        // Create an category object
        $category = new Category($this->conn);
        $category->id = $this->categoryId;

        // Attempt to read that single category record
        $category->readSingle();

        // If an category name exists in the category object now, the record exists, return true
        if ($category->category) return true;
        else return false;
    }

    /**
     * Function to check if a quote exists in the database
     * 
     * @return {boolean} - True if author exists in database
     */
    public function quoteExists()
    {
        include_once '../../models/Category.php';

        // Attempt to read the quote for the id in the current object
        $result = $this->readSingle(); // returns the statement after executing db query

        // If a row was returned return true
        if ($result->rowCount()) return true;
        else return false;
    }
}
