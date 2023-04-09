<?php

/**
 * Class to handle articles -- ACTIVE RECORD PATTERN
 */

class Article
{

  // Properties

  /**
   * @var int The article ID from the database
   */
  public $id = null;

  /**
   * @var int When the article was published
   */
  public $publicationDate = null;

  /**
   * @var int The article category ID
   */
  public $categoryId = null;

  /**
   * @var string Full title of the article
   */
  public $title = null;

  /**
   * @var string A short summary of the article
   */
  public $summary = null;

  /**
   * @var string The HTML content of the article
   */
  public $content = null;

  /**
   * @var string The Additional content of the article - nutricional informations, calories, etc.
   */
  public $additional = null;
  /**
   * @var string The filename extension of the article's full-size and thumbnail images (empty string means the article has no image)
   */
  public $imageExtension = "";

  /**
   * @var string The author of the article(automatically added by logged user)
   */
  public $author = null;

  /**
   * Sets the object's properties using the values in the supplied array
   *
   * @param assoc The property values
   */

  public function __construct($data = array())
  {
    if (isset($data['id'])) $this->id = (int) $data['id'];
    if (isset($data['publicationDate'])) $this->publicationDate = (int) $data['publicationDate'];
    if (isset($data['categoryId'])) $this->categoryId = (int) $data['categoryId'];
    if (isset($data['title'])) $this->title = preg_replace("/[\/\&%#\$]/", "", $data['title']);
    if (isset($data['summary'])) $this->summary = preg_replace("/[\/\&%#\$]/", "", $data['summary']);
    if (isset($data['content'])) $this->content = preg_replace("/[\&%#\$]/", "", $data['content']);
    if (isset($data['imageExtension'])) $this->imageExtension = preg_replace("/[^\.\,\-\_\'\"\@\?\!\$ a-zA-Z0-9()]/", "", $data['imageExtension']);
    if (isset($data['additional'])) $this->additional = $data['additional'];
    if (isset($data['author'])) $this->author = preg_replace("/[\/\&%#\$]/", "", $data['author']);
  }


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
   * Stores any image uploaded from the edit form to the fullsize folder
   * Generates a thumbnail version and stores it to the thumbnail folder
   *
   * @param assoc The 'image' element from the $_FILES array containing the file upload data
   */

  public function storeUploadedImage($image)
  {

    if ($image['error'] == UPLOAD_ERR_OK) {
      // Does the Article object have an ID?
      if (is_null($this->id)) trigger_error("Article::storeUploadedImage(): Attempt to upload an image for an Article object that does not have its ID property set.", E_USER_ERROR);

      // Delete any previous image(s) for this article
      $this->deleteImages();

      // Get and store the image filename extension
      $this->imageExtension = strtolower(strrchr($image['name'], '.'));

      // Store the image
      $tempFilename = trim($image['tmp_name']);
      if (is_uploaded_file($tempFilename)) {
        if (!(move_uploaded_file($tempFilename, $this->getImagePath()))) trigger_error("Article::storeUploadedImage(): Couldn't move uploaded file.", E_USER_ERROR);
        if (!(chmod($this->getImagePath(), 0666))) trigger_error("Article::storeUploadedImage(): Couldn't set permissions on uploaded file.", E_USER_ERROR);
      }

      // Get the image size and type
      $attrs = getimagesize($this->getImagePath());
      $imageWidth = $attrs[0];
      $imageHeight = $attrs[1];
      $imageType = $attrs[2];

      // echo (var_dump($imageType));
      // echo ($attrs[0]);

      // echo ($attrs[1]);
      // echo ($attrs[2]);
      // echo ($tempFilename);


      // Load the image into memory
      switch ($imageType) {
        case IMAGETYPE_GIF:
          $imageResource = imagecreatefromgif($this->getImagePath());
          break;
        case IMAGETYPE_JPEG:
          $imageResource = imagecreatefromjpeg($this->getImagePath());
          break;
        case IMAGETYPE_PNG:
          $imageResource = imagecreatefrompng($this->getImagePath());
          break;
        default:
          trigger_error("Article::storeUploadedImage(): Unhandled or unknown image type ($imageType)", E_USER_ERROR);
      }



      // Copy and resize the image to create the thumbnail
      $thumbHeight = intval($imageHeight / $imageWidth * ARTICLE_THUMB_WIDTH);
      $thumbResource = imagecreatetruecolor(ARTICLE_THUMB_WIDTH, $thumbHeight);
      imagecopyresampled($thumbResource, $imageResource, 0, 0, 0, 0, ARTICLE_THUMB_WIDTH, $thumbHeight, $imageWidth, $imageHeight);

      // Save the thumbnail
      switch ($imageType) {
        case IMAGETYPE_GIF:
          imagegif($thumbResource, $this->getImagePath(IMG_TYPE_THUMB));
          break;
        case IMAGETYPE_JPEG:
          imagejpeg($thumbResource, $this->getImagePath(IMG_TYPE_THUMB), JPEG_QUALITY);
          break;
        case IMAGETYPE_PNG:
          imagepng($thumbResource, $this->getImagePath(IMG_TYPE_THUMB));
          break;
        default:
          trigger_error("Article::storeUploadedImage(): Unhandled or unknown image type ($imageType)", E_USER_ERROR);
      }

      $this->update();
    } // else echo ($image['error']);
  }


  /**
   * Deletes any images and/or thumbnails associated with the article
   */

  public function deleteImages()
  {

    // Delete all fullsize images for this article
    foreach (glob(ARTICLE_IMAGE_PATH . "/" . IMG_TYPE_FULLSIZE . "/" . $this->id . ".*") as $filename) {
      if (!unlink($filename)) trigger_error("Article::deleteImages(): Couldn't delete image file.", E_USER_ERROR);
    }

    // Delete all thumbnail images for this article
    foreach (glob(ARTICLE_IMAGE_PATH . "/" . IMG_TYPE_THUMB . "/" . $this->id . ".*") as $filename) {
      if (!unlink($filename)) trigger_error("Article::deleteImages(): Couldn't delete thumbnail file.", E_USER_ERROR);
    }

    // Remove the image filename extension from the object
    $this->imageExtension = "";
  }


  /**
   * Returns the relative path to the article's full-size or thumbnail image
   *
   * @param string   The type of image path to retrieve (IMG_TYPE_FULLSIZE or IMG_TYPE_THUMB). Defaults to IMG_TYPE_FULLSIZE.
   * @return string|false The image's path, or false if an image hasn't been uploaded
   */

  public function getImagePath($type = IMG_TYPE_FULLSIZE)
  {
    return ($this->id && $this->imageExtension) ? (ARTICLE_IMAGE_PATH . "/$type/" . $this->id . $this->imageExtension) : false;
  }





  /**
   * Returns an Article object matching the given article ID
   *
   * @param int The article ID
   * @return Article|false The article object, or false if the record was not found or there was a problem
   */

  public static function getById($id)
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM articles WHERE id = :id";
    $st = $conn->prepare($sql);
    $st->bindValue(":id", $id, PDO::PARAM_INT);
    $st->execute();
    $row = $st->fetch();
    $conn = null;
    if ($row) return new Article($row);
  }


  /**
   * Returns an array of Article object matching the given article IDS
   *
   * @param array The article IDS
   * @return Array|false Array with article objects or false if the ID did not match any articles
   */

  public static function getByIds($arrayOfIds)
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM articles WHERE id = :id";
    $st = $conn->prepare($sql);

    $list = array();

    foreach ($arrayOfIds as $id) {   // Iterate through the array
      $st->bindValue(":id", $id, PDO::PARAM_INT);
      $st->execute();   // Execute for each ID

      $row = $st->fetch();
      $article = new Article($row);
      $list[] = $article;
    }
    $conn = null;
    return (array("results" => $list));
  }


  /**
   * Returns all (or a range of) Article objects in the DB matching the given summary
   *
   * @param string The article summary
   * @return Article|false The article object, or false if the record was not found or there was a problem
   */

  public static function getBySummary($summary)
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM articles WHERE summary = :summary";
    $st = $conn->prepare($sql);
    $st->bindValue(":summary", $summary, PDO::PARAM_STR);
    $st->execute();

    $list = array();

    while ($row = $st->fetch()) {
      $article = new Article($row);
      $list[] = $article;
    }

    $conn = null;
    return (array("results" => $list));
  }



  /**
   * Returns all (or a range of) Article objects in the DB
   *
   * @param int Optional The number of rows to return (default=all)
   * @param int Optional Return just articles in the category with this ID
   * @return Array|false A two-element array : results => array, a list of Article objects; totalRows => Total number of articles
   */

  public static function getList($numRows = 1000000, $categoryId = null)
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $categoryClause = $categoryId ? "WHERE categoryId = :categoryId" : "";
    $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(publicationDate) AS publicationDate
            FROM articles $categoryClause
            ORDER BY publicationDate DESC LIMIT :numRows";

    $st = $conn->prepare($sql);
    $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
    if ($categoryId) $st->bindValue(":categoryId", $categoryId, PDO::PARAM_INT);
    $st->execute();
    $list = array();

    //retrieve all rows from the multiple db ocurances from fetch and storing them in array
    while ($row = $st->fetch()) { //retrieve the quote record
      $article = new Article($row);
      $list[] = $article;
    }

    // Now get the total number of articles that matched the criteria
    $sql = "SELECT FOUND_ROWS() AS totalRows";
    $totalRows = $conn->query($sql)->fetch();
    $conn = null;
    return (array("results" => $list, "totalRows" => $totalRows[0]));
  }

  /**
   * Inserts the current(NEW) Article object into the database, and sets its ID property.
   */

  public function insert()
  {
    // Does the Article object already have an ID?
    if (!is_null($this->id)) trigger_error("Article::insert(): Attempt to insert an Article object that already has its ID property set (to $this->id).", E_USER_ERROR);

    // Insert the Article
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "INSERT INTO articles ( publicationDate, categoryId, title, summary, content, additional,imageExtension, author ) VALUES ( FROM_UNIXTIME(:publicationDate), :categoryId, :title, :summary, :content, :additional, :imageExtension, :author)";
    $st = $conn->prepare($sql);
    $st->bindValue(":publicationDate", $this->publicationDate, PDO::PARAM_INT);
    $st->bindValue(":categoryId", $this->categoryId, PDO::PARAM_INT);
    $st->bindValue(":title", $this->title, PDO::PARAM_STR);
    $st->bindValue(":summary", $this->summary, PDO::PARAM_STR);
    $st->bindValue(":content", $this->content, PDO::PARAM_STR);
    $st->bindValue(":additional", $this->additional, PDO::PARAM_STR);
    $st->bindValue(":imageExtension", $this->imageExtension, PDO::PARAM_STR);
    $st->bindValue(":author", $this->author, PDO::PARAM_STR);
    $st->execute();
    $this->id = $conn->lastInsertId();
    $conn = null;
  }


  /**
   * Updates the current Article object in the database.
   */

  public function update()
  {

    // Does the Article object have an ID?
    if (is_null($this->id)) trigger_error("Article::update(): Attempt to update an Article object that does not have its ID property set.", E_USER_ERROR);

    // Update the Article
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "UPDATE articles SET publicationDate=FROM_UNIXTIME(:publicationDate), categoryId=:categoryId, title=:title, summary=:summary, content=:content, additional=:additional, imageExtension=:imageExtension WHERE id = :id";
   

    $st = $conn->prepare($sql);
    $st->bindValue(":publicationDate", $this->publicationDate, PDO::PARAM_INT);
    $st->bindValue(":categoryId", $this->categoryId, PDO::PARAM_INT);
    $st->bindValue(":title", $this->title, PDO::PARAM_STR);
    $st->bindValue(":summary", $this->summary, PDO::PARAM_STR);
    $st->bindValue(":content", $this->content, PDO::PARAM_STR);
    $st->bindValue(":additional", $this->additional, PDO::PARAM_STR);
    $st->bindValue(":imageExtension", $this->imageExtension, PDO::PARAM_STR);
    $st->bindValue(":id", $this->id, PDO::PARAM_INT);
    //d($st->queryString);
    //exit;
    $st->execute();
    $conn = null;
  }




  /**
   * Deletes the current Article object from the database.
   */

  public function delete()
  {

    // Does the Article object have an ID?
    if (is_null($this->id)) trigger_error("Article::delete(): Attempt to delete an Article object that does not have its ID property set.", E_USER_ERROR);

    // Delete the Article
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $st = $conn->prepare("DELETE FROM articles WHERE id = :id LIMIT 1");
    $st->bindValue(":id", $this->id, PDO::PARAM_INT);
    $st->execute();
    $conn = null;
  }


  /**
   * Returns all (or a range of) Article objects in the DB matching the given CategoryID
   *
   * @param int The CategoryID
   * @return Article|false The article object, or false if the record was not found or there was a problem
   */

  public static function getArticleByCat($categoryId)
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM articles WHERE categoryId = :categoryId";
    $st = $conn->prepare($sql);
    $st->bindValue(":categoryId", $categoryId, PDO::PARAM_INT);
    $st->execute();

    $list = array();

    while ($row = $st->fetch()) {
      $article = new Article($row);
      $list[] = $article;
    }

    $conn = null;
    return (array("results" => $list));
  }


  /**
   * Returns all (or a range of) Article objects in the DB matching the given username
   *
   * @param int The username
   * @return Article|false The article object, or false if the record was not found or there was a problem
   */

  public static function getArticleByUsername($username)
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM articles WHERE author = :username";
    $st = $conn->prepare($sql);
    $st->bindValue(":username", $username, PDO::PARAM_STR);
    $st->execute();

    $list = array();

    while ($row = $st->fetch()) {
      $article = new Article($row);
      $list[] = $article;
    }

    $conn = null;
    return (array("results" => $list));
  }


  /**
   * Returns the CategoryID in the DB matching the given ArticleID
   *
   * @param int The ArticleID
   * @return int|false The categoryID, or false if the record was not found or there was a problem
   */

  public static function getCatFromArticle($articleID)
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM articles WHERE id = :articleID";
    $st = $conn->prepare($sql);
    $st->bindValue(":articleID", $articleID, PDO::PARAM_INT);
    $st->execute();

    $row = $st->fetch();
    $article = new Article($row);
    $categoryID = $article->categoryId;

    $conn = null;
    return $categoryID;
  }


  /**
   * Returns the next Article in array of Articles in the same category
   *
   * @param int The ArticleID
   * @return Article|false The Article object or false if the record was not found or there was a problem
   */

  public static function getPreviousArticleId($articleID)
  {
    $categoryIdOfSelectedArticle = Article::getCatFromArticle($articleID);

    $allArticlesInCategory = Article::getList(10000, $categoryIdOfSelectedArticle);

    $numberOfArticlesInCategory = count($allArticlesInCategory['results']);
    $listofArticlesInCategory = array();

    $x = 0;
    $currentArticleId = 0;
    $previousArticleId = 0;


    for ($x; $x < count($allArticlesInCategory['results']); $x++) {
      if ($allArticlesInCategory['results'][$x]->id == $articleID) {
        $currentArticleId =  $allArticlesInCategory['results'][$x]->id;

        if ($x > 0 && $x < $numberOfArticlesInCategory - 1) {
          $previousArticleId = $allArticlesInCategory['results'][$x + 1]->id;
        } else if ($x == 0  && $x < $numberOfArticlesInCategory - 1) {
          $previousArticleId = $allArticlesInCategory['results'][$x + 1]->id;
        } else if ($x > 0 && $x == $numberOfArticlesInCategory - 1) {
          $previousArticleId = "LAST ARTICLE";
        }
        return $previousArticleId;
      }
    }

    return $previousArticleId;
  }


  /**
   * Returns the next Article in array of Articles in the same category
   *
   * @param int The ArticleID
   * @return Article|false The Article object or false if the record was not found or there was a problem
   */

  public static function getNextArticleId($articleID)
  {
    $categoryIdOfSelectedArticle = Article::getCatFromArticle($articleID);

    $allArticlesInCategory = Article::getList(10000, $categoryIdOfSelectedArticle);

    $numberOfArticlesInCategory = count($allArticlesInCategory['results']);
    $listofArticlesInCategory = array();

    $x = 0;
    $currentArticleId = 0;
    $nextArticleId = 0;


    for ($x; $x < count($allArticlesInCategory['results']); $x++) {
      if ($allArticlesInCategory['results'][$x]->id == $articleID) {
        $currentArticleId =  $allArticlesInCategory['results'][$x]->id;

        if ($x > 0 && $x < $numberOfArticlesInCategory - 1) {
          $nextArticleId = $allArticlesInCategory['results'][$x - 1]->id;
        } else if ($x == 0  && $x < $numberOfArticlesInCategory - 1) {
          $nextArticleId = "LAST ARTICLE";
        } else if ($x > 0 && $x == $numberOfArticlesInCategory - 1) {
          $nextArticleId = $allArticlesInCategory['results'][$x - 1]->id;
        }
        return $nextArticleId;
      }
    }

    return $nextArticleId;
  }


  /**
   * Returns the number of Article objects in the DB matching the given CategoryID
   *
   * @param int The CategoryID
   * @return int The the number of Article objects in the DB matching the given CategoryID
   */

  public static function getNumOfArticleByCat($categoryId)
  {
    return count(Article::getArticleByCat($categoryId)['results']);
  }



  /**
   * Returns the number of Article objects in the DB matching the given username
   *
   * @param int The username
   * @return int The the number of Article objects in the DB matching the given username
   */

  public static function getNumOfArticlesByUser($username)
  {
    return count(Article::getArticleByUsername($username)['results']);
  }


  /**
   * Returns an array of Article objects in the DB matching the given search query
   *
   * @param array array with q - query, s - step , n - pagenumber
   * @return Article|false The article object, or false if the record was not found or there was a problem
   */

  public static function searchArticles($search)
  {

    /*     $data = array();
        $data['q'] = 'polievka'; */

        /* $data['q'] = $_GET['q']; */
        /*    $data['s'] = '20';
        $data['n'] = '10';
    */
    $q = $search['q'];
    $s = $search['s'];
    $n = $search['n'];

    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT *, UNIX_TIMESTAMP(publicationDate) AS publicationDate FROM articles WHERE (title LIKE CONCAT( '%', :q, '%') OR summary LIKE  CONCAT( '%', :q, '%') OR content LIKE  CONCAT( '%', :q, '%') )";
    /*  ORDER BY ID DESC LIMIT ':s' , ':n' "; */
    $st = $conn->prepare($sql);
    $st->bindValue(":q", $q, PDO::PARAM_STR);
    $st->bindValue(":s", $s, PDO::PARAM_INT);
    $st->bindValue(":n", $n, PDO::PARAM_INT);
    $st->execute();

    $list = array();

    while ($row = $st->fetch()) {
      $article = new Article($row);
      $list[] = $article;
    }

    $conn = null;
    return (array("results" => $list));
  }

  /**
   * Sort a multi-domensional array of objects by key value
   * Usage: usort($array, arrSortObjsByKey('VALUE_TO_SORT_BY'));
   * Expects an array of objects.
   *
   * @param String    $key  The name of the parameter to sort by
   * @param String 	$order the sort order
   * @return A function to compare using usort
   */
  public static function arrSortObjsByKey($key, $order = 'DESC')
  {
    return function ($a, $b) use ($key, $order) {

      // Swap order if necessary
      if ($order == 'DESC') {
        list($a, $b) = array($b, $a);
      }

      // Check data type
      if (is_numeric($a->$key)) {
        return $a->$key - $b->$key; // compare numeric
      } else {
        return strnatcasecmp($a->$key, $b->$key); // compare string
      }
    };
  }
}