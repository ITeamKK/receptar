<?php

/**
 * Class to handle article categories
 */

class Category
{
  // Properties

  /**
  * @var int The category ID from the database
  */
  public $id = null;

  /**
  * @var string Name of the category
  */
  public $name = null;

  /**
  * @var string A short description of the category
  */
  public $description = null;


  /**
  * Sets the object's properties using the values in the supplied array
  *
  * @param assoc The property values
  */

  public function __construct( $data=array() ) {
    if ( isset( $data['id'] ) ) $this->id = (int) $data['id'];
    if ( isset( $data['name'] ) ) $this->name = preg_replace ( "/[\/\&%#\$]/", "", $data['name'] );
    if ( isset( $data['description'] ) ) $this->description = preg_replace ( "/[\/\&%#\$]/", "", $data['description'] );
  }


  /**
  * Sets the object's properties using the edit form post values in the supplied array
  *
  * @param assoc The form post values
  */

  public function storeFormValues ( $params ) {

    // Store all the parameters
    $this->__construct( $params );
  }


  /**
  * Returns a Category object matching the given category ID
  *
  * @param int The category ID
  * @return Category|false The category object, or false if the record was not found or there was a problem
  */

  public static function getById( $id ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT * FROM categories WHERE id = :id";
    $st = $conn->prepare( $sql );
    $st->bindValue( ":id", $id, PDO::PARAM_INT );
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ( $row ) return new Category( $row );
  }


  /**
  * Returns all (or a range of) Category objects in the DB
  *
  * @param int Optional The number of rows to return (default=all)
  * @return Array|false A two-element array : results => array, a list of Category objects; totalRows => Total number of categories
  */

  public static function getList( $numRows=1000000 ) {
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM categories
            ORDER BY name ASC LIMIT :numRows";

    $st = $conn->prepare( $sql );
    $st->bindValue( ":numRows", $numRows, PDO::PARAM_INT );
    $st->execute();
    $list = array();

    while ( $row = $st->fetch() ) {
      $category = new Category( $row );
      $list[] = $category;
    }

    // Now get the total number of categories that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query( $sql )->fetch();
    $conn = null;
    return ( array ( "results" => $list, "totalRows" => $totalRows[0] ) );
  }


  /**
  * Inserts the current Category object into the database, and sets its ID property.
  */

  public function insert() {

    // Does the Category object already have an ID?
    if ( !is_null( $this->id ) ) trigger_error ( "Category::insert(): Attempt to insert a Category object that already has its ID property set (to $this->id).", E_USER_ERROR );

    // Insert the Category
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "INSERT INTO categories ( name, description ) VALUES ( :name, :description )";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
    $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }


  /**
  * Updates the current Category object in the database.
  */

  public function update() {

    // Does the Category object have an ID?
    if ( is_null( $this->id ) ) trigger_error ( "Category::update(): Attempt to update a Category object that does not have its ID property set.", E_USER_ERROR );
   
    // Update the Category
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $sql = "UPDATE categories SET name=:name, description=:description WHERE id = :id";
    $st = $conn->prepare ( $sql );
    $st->bindValue( ":name", $this->name, PDO::PARAM_STR );
    $st->bindValue( ":description", $this->description, PDO::PARAM_STR );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }


  /**
  * Deletes the current Category object from the database.
  */

  public function delete() {

    // Does the Category object have an ID?
    if ( is_null( $this->id ) ) trigger_error ( "Category::delete(): Attempt to delete a Category object that does not have its ID property set.", E_USER_ERROR );

    // Delete the Category
    $conn = new PDO( DB_DSN, DB_USERNAME, DB_PASSWORD );
    $st = $conn->prepare ( "DELETE FROM categories WHERE id = :id LIMIT 1" );
    $st->bindValue( ":id", $this->id, PDO::PARAM_INT );
    $st->execute();
    $conn = null;
  }





}

?>