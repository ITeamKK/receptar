<?php

/**
 * Class to handle reset password requests
 */

class Security
{

    // Properties

    /**
     * @var int The Security ID from the database
     */
    public $id = null;

    /**
     * @var string Email of user who forgot password
     */
    public $email = null;

    /**
     * @var string token randomly generated to confirm email belongs to locked out user
     */
    public $token = null;


    /**
     * Sets the object's properties using the values in the supplied array
     *
     * @param assoc The property values
     */

    public function __construct($data = array())
    {
        if (isset($data['id'])) $this->id = (int) $data['id'];
        if (isset($data['email'])) $this->email = preg_replace("/[\/\&%#\$]/", "", $data['email']);
        if (isset($data['token'])) $this->token = (string) $data['token'];
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
    }


    /**
     * Inserts the current User object into the database, and sets its ID property.
     */

    public function insert()
    {
        // Insert the Password reset request
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "INSERT INTO password_resets (email, token) VALUES ( :email, :token)";
        $st = $conn->prepare($sql);
        $st->bindValue(":email", $this->email, PDO::PARAM_STR);
        $st->bindValue(":token", $this->token, PDO::PARAM_STR);
        $st->execute();
        $this->id = $conn->lastInsertId();
        $conn = null;
    }




    /**
     * Returns an User object matching the given User ID
     *
     * @param int The User ID
     * @return User|false The User object, or false if the record was not found or there was a problem
     */

    // public static function getById($id)
    // {
    //     $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    //     $sql = "SELECT *, UNIX_TIMESTAMP(registrationDate) AS registrationDate FROM users WHERE id = :id";
    //     $st = $conn->prepare($sql);
    //     $st->bindValue(":id", $id, PDO::PARAM_INT);
    //     $st->execute();
    //     $row = $st->fetch();
    //     $conn = null;
    //     if ($row) return new User($row);
    // }


    /* *
     * Returns the token from the object in the DB matching the given token
     *
     * @param string The User token
     * @return Security object|false if the record was not found or there was a problem
     */

    public static function getByToken($token)
  {
    $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    $sql = "SELECT * FROM password_resets WHERE token = :token";
    $st = $conn->prepare($sql);
    $st->bindValue(":token", $token, PDO::PARAM_STR);
    $st->execute();
    $row = $st->fetch();
   // $data = new Security($row);
    $conn = null;
    if ($row) return new Security($row);
  }

    /**
     * Returns the Security object in the DB matching the given email
     *
     * @param string The Security email
     * @return Security|false Security object, or false if the record was not found or there was a problem
     */

    public static function getByEmail($email)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "SELECT * FROM password_resets WHERE email = :email";
        $st = $conn->prepare($sql);
        $st->bindValue(":email", $email, PDO::PARAM_STR);
        $st->execute();
        $row = $st->fetch();
        $conn = null;
        if ($row) return new Security($row);
    }


    /**
     * Returns all (or a range of) User objects in the DB
     *
     * @param int Optional The number of rows to return (default=all)
     * @param int Optional Return just Users in the category with this ID
     * @return Array|false A two-element array : results => array, a list of User objects; totalRows => Total number of Users
     */

    public static function getList($numRows = 1000000, $categoryId = null)
    {
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $categoryClause = $categoryId ? "WHERE categoryId = :categoryId" : "";
        $sql = "SELECT SQL_CALC_FOUND_ROWS *, UNIX_TIMESTAMP(registrationDate) AS registrationDate
            FROM users $categoryClause
            ORDER BY registrationDate DESC LIMIT :numRows";

        $st = $conn->prepare($sql);
        $st->bindValue(":numRows", $numRows, PDO::PARAM_INT);
        if ($categoryId) $st->bindValue(":categoryId", $categoryId, PDO::PARAM_INT);
        $st->execute();
        $list = array();

        //retrieve all rows from the multiple db ocurances from fetch and storing them in array
        while ($row = $st->fetch()) { //retrieve the quote record
            $User = new User($row);
            $list[] = $User;
        }

        // Now get the total number of Users that matched the criteria
        $sql = "SELECT FOUND_ROWS() AS totalRows";
        $totalRows = $conn->query($sql)->fetch();
        $conn = null;
        return (array("results" => $list, "totalRows" => $totalRows[0]));
    }



    /**
     * Updates the current User object in the database.
     */

    public function update()
    {

        // Does the User object have an ID?
        if (is_null($this->id)) trigger_error("User::update(): Attempt to update an User object that does not have its ID property set.", E_USER_ERROR);

        // Update the User
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $sql = "UPDATE users SET username=:username, password=:password, role=:role, favouriteArticles=:favouriteArticles, imageExtension=:imageExtension, aboutUser=:aboutUser, email=:email WHERE id = :id";
        $st = $conn->prepare($sql);
        $st->bindValue(":username", $this->username, PDO::PARAM_STR);
        $st->bindValue(":password", $this->password, PDO::PARAM_STR);
        $st->bindValue(":role", $this->role, PDO::PARAM_INT);
        $st->bindValue(":favouriteArticles", $this->favouriteArticles, PDO::PARAM_STR);
        $st->bindValue(":imageExtension", $this->imageExtension, PDO::PARAM_STR);
        $st->bindValue(":aboutUser", $this->aboutUser, PDO::PARAM_STR);
        $st->bindValue(":email", $this->email, PDO::PARAM_STR);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $conn = null;
    }




    /**
     * Deletes the current User object from the database.
     */

    public function deleteUserByUsername()
    {

        // Does the User object have an username?
        if (is_null($this->username)) trigger_error("User::delete(): Attempt to delete an User object that does not have its username property set.", E_USER_ERROR);

        // Delete the User
        $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
        $st = $conn->prepare("DELETE FROM users WHERE username = :username LIMIT 1");
        $st->bindValue(":username", $this->username, PDO::PARAM_STR);
        $st->execute();
        $conn = null;
    }


    // /*   *
    //    * Returns all (or a range of) User objects in the DB matching the given CategoryID
    //    *
    //    * @param int The CategoryID
    //    * @return User|false The User object, or false if the record was not found or there was a problem
    //    */

    //   public static function getUserByCat($categoryId)
    //   {
    //     $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    //     $sql = "SELECT *, UNIX_TIMESTAMP(registrationDate) AS registrationDate FROM users WHERE categoryId = :categoryId";
    //     $st = $conn->prepare($sql);
    //     $st->bindValue(":categoryId", $categoryId, PDO::PARAM_INT);
    //     $st->execute();

    //     $list = array();

    //     while ($row = $st->fetch()) {
    //       $User = new User($row);
    //       $list[] = $User;
    //     }

    //     $conn = null;
    //     return (array("results" => $list));
    //   }


    //   /**
    //    * Returns the CategoryID in the DB matching the given UserID
    //    *
    //    * @param int The UserID
    //    * @return int|false The categoryID, or false if the record was not found or there was a problem
    //    */

    //   public static function getCatFromUser($UserID)
    //   {
    //     $conn = new PDO(DB_DSN, DB_USERNAME, DB_PASSWORD);
    //     $sql = "SELECT *, UNIX_TIMESTAMP(registrationDate) AS registrationDate FROM users WHERE id = :UserID";
    //     $st = $conn->prepare($sql);
    //     $st->bindValue(":UserID", $UserID, PDO::PARAM_INT);
    //     $st->execute();

    //     $row = $st->fetch();
    //     $User = new User($row);
    //     $categoryID = $User->categoryId;

    //     $conn = null;
    //     return $categoryID;
    //   }


    //   /**
    //    * Returns the next User in array of Users in the same category
    //    *
    //    * @param int The UserID
    //    * @return User|false The User object or false if the record was not found or there was a problem
    //    */

    //   public static function getNextUserId($UserID)
    //   {
    //     $categoryIdOfSelectedUser = User::getCatFromUser($UserID);

    //     $allUsersInCategory = User::getList(10000, $categoryIdOfSelectedUser);

    //     $numberOfUsersInCategory = count($allUsersInCategory['results']);
    //     $listofUsersInCategory = array();

    //     $x = 0;
    //     $currentUserId = 0;
    //     $nextUserId = 0;


    //     for ($x; $x < count($allUsersInCategory['results']); $x++) {
    //       if ($allUsersInCategory['results'][$x]->id == $UserID) {
    //         $currentUserId =  $allUsersInCategory['results'][$x]->id;

    //         if ($x > 0 && $x < $numberOfUsersInCategory - 1) {
    //           $nextUserId = $allUsersInCategory['results'][$x + 1]->id;
    //         } else if ($x == 0  && $x < $numberOfUsersInCategory - 1) {
    //           $nextUserId = $allUsersInCategory['results'][$x + 1]->id;
    //         } else if ($x > 0 && $x == $numberOfUsersInCategory - 1) {
    //           $nextUserId = "LAST User";
    //         }
    //         return $nextUserId;
    //       }
    //     }

    //     return $nextUserId;
    //   }


    //   /**
    //    * Returns the next User in array of Users in the same category
    //    *
    //    * @param int The UserID
    //    * @return User|false The User object or false if the record was not found or there was a problem
    //    */

    //   public static function getPreviousUserId($UserID)
    //   {
    //     $categoryIdOfSelectedUser = User::getCatFromUser($UserID);

    //     $allUsersInCategory = User::getList(10000, $categoryIdOfSelectedUser);

    //     $numberOfUsersInCategory = count($allUsersInCategory['results']);
    //     $listofUsersInCategory = array();

    //     $x = 0;
    //     $currentUserId = 0;
    //     $previousUserId = 0;


    //     for ($x; $x < count($allUsersInCategory['results']); $x++) {
    //       if ($allUsersInCategory['results'][$x]->id == $UserID) {
    //         $currentUserId =  $allUsersInCategory['results'][$x]->id;

    //         if ($x > 0 && $x < $numberOfUsersInCategory - 1) {
    //           $previousUserId = $allUsersInCategory['results'][$x - 1]->id;
    //         } else if ($x == 0  && $x < $numberOfUsersInCategory - 1) {
    //           $previousUserId = "LAST User";
    //         } else if ($x > 0 && $x == $numberOfUsersInCategory - 1) {
    //           $previousUserId = $allUsersInCategory['results'][$x - 1]->id;
    //         }
    //         return $previousUserId;
    //       }
    //     }

    //     return $previousUserId;
    //   }


    // /**
    //  * Returns the number of User objects in the DB matching the given CategoryID
    //  *
    //  * @param int The CategoryID
    //  * @return int The the number of User objects in the DB matching the given CategoryID
    //  */

    // public static function getNumOfUserByCat($categoryId)
    // {
    //   return count(User::getUserByCat($categoryId)['results']);
    // }



    /**
     * Returns an array of User objects in the DB matching the given search query
     *
     * @param array array with q - query, s - step , n - pagenumber
     * @return User|false The User object, or false if the record was not found or there was a problem
     */

    public static function searchUsers($search)
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
        $sql = "SELECT *, UNIX_TIMESTAMP(registrationDate) AS registrationDate FROM users WHERE (username LIKE CONCAT( '%', :q, '%') OR password LIKE  CONCAT( '%', :q, '%') OR content LIKE  CONCAT( '%', :q, '%') )";
        /*  ORDER BY ID DESC LIMIT ':s' , ':n' "; */
        $st = $conn->prepare($sql);
        $st->bindValue(":q", $q, PDO::PARAM_STR);
        $st->bindValue(":s", $s, PDO::PARAM_INT);
        $st->bindValue(":n", $n, PDO::PARAM_INT);
        $st->execute();

        $list = array();

        while ($row = $st->fetch()) {
            $User = new User($row);
            $list[] = $User;
        }

        $conn = null;
        return (array("results" => $list));
    }


    /**
     * Returns array of favourites articles (id's) for User by username
     *
     * @param string username
     * @return array|false The User favourites array, or false if the record was not found or there was a problem
     */
    public static function getFavouritesByUsername($username)
    {
        $stringFavouritesArticles = User::getByUsername($username)->favouriteArticles;
        $arrayFavouritesArticles = explode(",", $stringFavouritesArticles); //get array from DB string
        //we filter the array to get rid of empty strings
        $arrayFavouritesArticles = array_filter($arrayFavouritesArticles);
        // if (empty($arrayFavouritesArticles)) {
        //   $arrayFavouritesArticles = false;
        // }
        return $arrayFavouritesArticles;
    }


    /**
     * Stores any image uploaded from the USER edit form(in userChanges.php)
     *
     * @param assoc The 'image' element from the $_FILES array containing the file upload data
     */

    public function storeUploadedImage($image)
    {
        //checks for upload error(echo error?)
        if ($image['error'] == UPLOAD_ERR_OK) {
            // Does the User object have an ID?
            if (is_null($this->id)) trigger_error("User::storeUploadedImage(): Attempt to upload an image for an User object that does not have its ID property set.", E_USER_ERROR);

            // Delete any previous image(s) for this User
            $this->deleteImages();

            // Get and store the image filename extension
            $this->imageExtension = strtolower(strrchr($image['name'], '.'));

            // Store the image

            $tempFilename = trim($image['tmp_name']);

            if (is_uploaded_file($tempFilename)) {
                if (!(move_uploaded_file($tempFilename, $this->getImagePath()))) trigger_error("User::storeUploadedImage(): Couldn't move uploaded file.", E_USER_ERROR);
                if (!(chmod($this->getImagePath(), 0666))) trigger_error("User::storeUploadedImage(): Couldn't set permissions on uploaded file.", E_USER_ERROR);
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
                    trigger_error("User::storeUploadedImage(): Unhandled or unknown image type ($imageType)", E_USER_ERROR);
            }



            // Copy and resize the image to create the thumbnail
            $thumbHeight = intval($imageHeight / $imageWidth * USER_THUMB_WIDTH);
            $thumbResource = imagecreatetruecolor(USER_THUMB_WIDTH, $thumbHeight);
            imagecopyresampled($thumbResource, $imageResource, 0, 0, 0, 0, USER_THUMB_WIDTH, $thumbHeight, $imageWidth, $imageHeight);

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
                    trigger_error("User::storeUploadedImage(): Unhandled or unknown image type ($imageType)", E_USER_ERROR);
            }

            $this->update();
        } else {
            // echo ($image['error']);
        }
    }


    /**
     * Deletes any images and/or thumbnails associated with the User
     */
    public function deleteImages()
    {

        // Delete all fullsize images for this User
        foreach (glob(USER_IMAGE_PATH . "/" . IMG_TYPE_FULLSIZE . "/" . $this->id . ".*") as $filename) {
            if (!unlink($filename)) trigger_error("User::deleteImages(): Couldn't delete image file.", E_USER_ERROR);
        }

        // Delete all thumbnail images for this User
        foreach (glob(USER_IMAGE_PATH . "/" . IMG_TYPE_THUMB . "/" . $this->id . ".*") as $filename) {
            if (!unlink($filename)) trigger_error("User::deleteImages(): Couldn't delete thumbnail file.", E_USER_ERROR);
        }

        // Remove the image filename extension from the object
        $this->imageExtension = "";
    }


    /**
     * Returns the relative path to the User's full-size or thumbnail image
     *
     * @param string   The type of image path to retrieve (IMG_TYPE_FULLSIZE or IMG_TYPE_THUMB). Defaults to IMG_TYPE_FULLSIZE.
     * @return string|false The image's path, or false if an image hasn't been uploaded
     */

    public function getImagePath($type = IMG_TYPE_FULLSIZE)
    {
        return ($this->id && $this->imageExtension) ? (USER_IMAGE_PATH . "/$type/" . $this->id . $this->imageExtension) : false;
    }
}
