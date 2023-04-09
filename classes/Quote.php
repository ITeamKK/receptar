<?php

/**
 * Class to handle quotes -- ACTIVE RECORD PATTERN
 */

class Quote
{
    //PROPERTIES DECLARATION------------------------------------------------------
    /**
     * @var int The quote ID from the database
     */
    public $id = null;

    /**
     * @var int When the article was published
     */
    public $publicationDate = null;

    /**
     * @var string Author/UserName of the quote entry to the database
     */
    public $author = null;

    /**
     * @var string Quote content
     */
    public $quoteContent = null;


    //CONSTRUCTOR---------------------------------------------------------------
    /**
     * Sets the object's properties using the values in the supplied array
     *
     * @param assoc The property values
     */
    public function __construct($data = array())
    {
        if (isset($data['id'])) $this->id = (int) $data['id'];
        if (isset($data['publicationDate'])) $this->publicationDate = (int) $data['publicationDate'];
        if (isset($data['author'])) $this->author = preg_replace("/[\/\&%#\$]/", "", $data['author']);
        if (isset($data['quoteContent'])) $this->quoteContent = preg_replace("/[\/\&%#\$]/", "", $data['quoteContent']); //regex-> non letter characters will be replaced by ""
    }


    //METHODS---------------------------------------------------------------
    /**
     * Sets the object's properties using the edit form post values in the supplied array
     *
     * @param assoc The form post values
     */
    public function storeFormValues($params)
    {
        // Store all the parameters
        $this->__construct($params);

        // Parse and store the publication date
        if (isset($params['publicationDate'])) {
            $publicationDate = explode('-', $params['publicationDate']);

            if (count($publicationDate) == 3) {
                list($y, $m, $d) = $publicationDate;
                $this->publicationDate = mktime(0, 0, 0, $m, $d, $y);
            }
        }
    }


    /**
     * Returns an Quote object matching the given article ID
     *
     * @param int The article ID
     * @return Quote|false The article object, or false if the record was not found or there was a problem.
     *Static to enable our method to be called without needing an dummy object to be created every time we call the method
     */
    public static function getById($id)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD); //connect to the DB
        $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM quotes WHERE id = :id"; //retrieves the publicationDate field in UNIX timestamp format instead of the default MySQL date format, so we can store it easily in our object
        //:id = placeholder, Do not place $id parameter directly into SELECT, it can be a security risk
        $st = $conn->prepare($sql); //prepare statement is a feature of db, they allow db calls to be faster and more secure
        $st->bindValue(":id", $id, PDO::PARAM_INT); //binding :id placeholder to our variable $id
        $st->execute(); //run the query
        $row = $st->fetch(); //retrieve the quote record
        $conn = null; //closing connection
        if ($row) return new Article($row);
    }

    /**
     * Returns all Quote objects in the DB
     *
     * @return Array|false A two-element array : results => array, a list of Quote objects; totalRows => Total number of quotes
     */

    public static function getList()
    {
        $conn = new PDO(DB_DS_SLOVAK, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM quotes ORDER BY publicationDate DESC";
        $st = $conn->prepare($sql);
        $st->execute();
        $list = array();

        //retrieve all rows from the multiple db ocurances from fetch and storing them in array
        while ($row = $st->fetch()) { //retrieve the quote record
            $quote = new Quote($row);
            $list[] = $quote;
        }

        // Now get the total number of quotes that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return (array("results" => $list, "totalRows" => $totalRows[0]));
    }


    /**
     * Returns an Random Quote object
     *
     * @return Quote|false The quote object, or false if the record was not found or there was a problem.
     */
    public static function getRandomQuote()
    {

        $allQuotesArray = Quote::getList();

        $minValue = 0;
        $maxValue = $allQuotesArray['totalRows'];


        $randomIndex = mt_rand($minValue, $maxValue-1); //random integer between minValue and maxValue (inclusive)

        return $allQuotesArray['results'][$randomIndex]->quoteContent;

    }


    /**
     * Inserts the current Quote record/object into the database.
     */
    public function insert()
    {
        // Does the Quote object already have an ID?/if yes, its already in db some content and we cant overwrite it
        if (!is_null($this->id)) trigger_error("Quote::insert(): Attempt to insert an Quote object that already has its ID property set (to $this->id).", E_USER_ERROR);

        //Insert the Quote
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);

        //SQL INSERT QUERY, using placeholders. MySQL FROM_UNIXTIME() to convert date from UNIX timestamp back to MySQL format
        $sql = "INSERT INTO quotes ( publicationDate, author, quoteContent ) VALUES ( FROM_UNIXTIME(:publicationDate), :author, :quoteContent )";
        $st = $conn->prepare($sql);
        $st->bindValue(":publicationDate", $this->publicationDate, PDO::PARAM_INT);
        $st->bindValue(":author", $this->author, PDO::PARAM_STR);
        $st->bindValue(":quoteContent", $this->quoteContent, PDO::PARAM_STR);
        $st->execute();
        $this->id = $conn->lastInsertId(); //Retrieve the new article record ID(autodegerated by db), it stores it in object $id property for future reference.
        $conn = null;
    }

    /**
     * Updates the current/existing Quote object in the database instead of creating a new record.
     */
    public function update()
    {
        //Does the Quote object have an ID, if not->error
        if (is_null($this->id)) trigger_error("Quote::update(): Attempt to update an Quote object that does not have its ID property set.", E_USER_ERROR);

        //Update the Quote
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "UPDATE quotes SET publicationDate=FROM_UNIXTIME(:publicationDate), author=:author, quoteContent=:quoteContent WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":publicationDate", $this->publicationDate, PDO::PARAM_INT);
        $st->bindValue(":author", $this->title, PDO::PARAM_STR);
        $st->bindValue(":quoteContent", $this->summary, PDO::PARAM_STR);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }


    /**
     * Deletes the current Article object from the database.
     */
    public function delete()
    {
        // Does the Quote object have an ID?
        if (is_null($this->id)) trigger_error("Quote::delete(): Attempt to delete an Quote object that does not have its ID property set.", E_USER_ERROR);

        //Delete the Quote
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $st = $conn->prepare("DELETE FROM quotes WHERE id = :id LIMIT 1"); //make sure that only 1 article record can be deleted at a time
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }
}
