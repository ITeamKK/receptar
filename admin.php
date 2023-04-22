<?php

session_start(); //musi by vykomentovany v localhost,inak vhttps://qpalma.myqnapcloud.com:8081/cms nie!
//Notice: session_start(): A session had already been started - ignoring in /var/www/html/cms/templates/include/header.php on line 1
require("config.php");

include 'kint.phar'; //Debugging
// d($_SESSION); //ked sa toto povoli, tak to zacne blbnut a neda sa prihlasit - nemoze tu byt ani print ani echo ked pouzivame header()
/*
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
*/
//if ther is no action set, like from correct login, action == empty and the next if wont do anything
$action = isset($_GET['action']) ? $_GET['action'] : "";
$username = isset($_SESSION['username']) ? $_SESSION['username'] : "";

//check if the user is logged, if the session exists = created by login function, $Username in the condition exists, is not empty, thus login() wont be invoked again
if ($action != "login" && $action != "logout" && $action != "register" && $action != "resetPassword" && !$username && $action != "setNewPassword") {
    login();
    exit;   //do not continue with the code
}

//if the action is not handles with the if above?
switch ($action) {
    case 'register':
        registerNewUser();  //https://qpalma.myqnapcloud.com:8081/cms/admin.php?action=register
        break;
    case 'login':
        login();            //https://qpalma.myqnapcloud.com:8081/cms/admin.php
        break;
    case 'logout':
        logout();           //http://localhost/cms/admin.php?action=logout
        break;
    case 'newArticle':
        newArticle();       //https://localhost/cms/admin.php?action=newArticle
        break;
    case 'editArticle':
        editArticle();      //https://localhost/cms/admin.php?action=editArticle&articleId=%%
        break;
    case 'deleteArticle':
        deleteArticle();    //https://localhost/cms/admin.php?action=deleteArticle&articleId=%
        break;
    case 'listCategories':
        listCategories();
        break;
    case 'newCategory':
        newCategory();
        break;
    case 'editCategory':
        editCategory();
        break;
    case 'deleteCategory':
        deleteCategory();
        break;
    case 'viewByCategoryLogged':
        viewByCategoryLogged();     //http://localhost/cms/homepage.php
        break;
    case 'userDetails':
        userDetails();
        break;
    case 'deleteUser':
        deleteUser();
        break;
    case 'favourites':
        viewFavourites();
        break;
    case 'resetPassword':
        resetPassword();
        break;
    case 'setNewPassword':
        setNewPassword();
        break;
    case 'dbManagement':
        dbManagement();
        break;
    case 'dbUpdate':
        dbUpdate();
        break;
    case 'dbBackup':
        dbBackup();
        break;
    default:
        //listArticles();
        viewByCategoryLogged();
}

/**
 * Sends mail to user according to 2 different forms->
 * Contact Form
 * Password "reset"
 * */
function resetPassword()
{
    
    $results = array();
    //check if email is filled out
    if (isset($_POST['email']) && isset($_POST['resetPasswordName'])) {

        $lockedOutUser = User::getByUsername($_POST['resetPasswordName']);

        if (empty($lockedOutUser)) {

            $results['errorMessageResetPassword'] = "Používateľ neexistuje.";
            require(TEMPLATE_PATH . "/admin/loginForm.php");
        } else if ($_POST['email'] != $lockedOutUser->email) {
            $results['errorMessageResetPassword'] = "Nesprávny email.";
            require(TEMPLATE_PATH . "/admin/loginForm.php");
        } else {

            // CREATE TOKEN AND INSERT INTO DB
            include 'templates/admin/include/resetPassword.php';

            $email_to = $lockedOutUser->email;
            $email_subject = "Zabudnuté heslo " . $GLOBALS['SITE_NAME'];

            $name = $lockedOutUser->username; // required
            $email = $lockedOutUser->email; // required
            $message = "Hi there, click on this <a href=\"https://qpalma.myqnapcloud.com:8081/cms/admin.php?action=setNewPassword&token=" . $resetPwdToken . "\">link</a> to reset your password on our site";

            include 'templates/include/sendmail.php';

            $results['successMessageResetPassword'] = "Na Vašu mailovú adresu Vám bol zaslany link pre reset hesla.";

            require(TEMPLATE_PATH . "/admin/loginForm.php");
        }
    }
}

function setNewPassword()
{
    $results = array();
    $results['pageTitle'] = "Zmena hesla";

    $articlesData = Article::getList();
    $results['totalRows'] = $articlesData['totalRows']; //for showing total # of article

    if (isset($_GET['token'])) {
        $tokenFromMail = $_GET['token'];
    } else if (isset($_POST['token'])) {
        $tokenFromMail = $_POST['token'];
    } else {
        $tokenFromMail = "";
    }

    //check for token authenticity
    //token exist and correct one, we let the user set a new password
    if ($userAffected = Security::getByToken($tokenFromMail)) {

        //user has entered input data from resetPass
        if ((isset($_POST['password']) && isset($_POST['password_repeat']))) {
            $params = array();

            //WRITING NEW NAME INTO VARIABLE
            $params['password'] = $_POST['password'];
            //UPDATING DB with new variable
            //Get User object from email
            $userToBeModified = User::getByEmail($userAffected->email);
            $userToBeModified->storeFormValues($params);
            $userToBeModified->update();

            $results['successMessageUsername'] = "Heslo zmenené.";

            if (isset($results['successMessageUsername'])) {
                $result = '
                      <script> alert("' . $results['successMessageUsername'] . '"); </script>';
                echo $result;
            }

            require(TEMPLATE_PATH . "/admin/loginForm.php");
        } else {

            //user has to enter a new password, show him the form

            require(TEMPLATE_PATH . "/admin/resetPassForm.php");
        }
    } else {
        //token not in db
        $results['errorMessageResetPassword'] = "Platnosť zmeny hesla vypršala.";
        require(TEMPLATE_PATH . "/admin/loginForm.php");
    }
}

/**
 * returns true if logged user is admin | false if not
 * */
function checkAdmin()
{
    // $admin = "defined by function";
    $admin = (User::getByUsername($_SESSION['username'])->role == 0) ? true : false;
    return $admin;
}

/**
 * registration of new user, reroutes to registrationForm.php
 * */
function registerNewUser()
{
    $results = array();
    $results['pageTitle'] = $GLOBALS['SITE_NAME'] . " | " . $GLOBALS['REGISTER FORM'];

    $articlesData = Article::getList();
    $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles

    // User has posted the register form: attempt to create user
    if (isset($_POST['register'])) {
        //search for the name atempted to be registred
        $currentUser = User::getByUsername((string) ($_POST['username']));

        //if true = the posted username doesnt exists in db, will continue
        if (!$currentUser) {

            //we create new variable for user and we would call function to store it into db
            $user = new User;
            $user->storeFormValues($_POST);
            $user->insert(); //inserts NEW data to the object User and to the db

            $insertedUser = User::getByUsername((string) ($_POST['username']));
            //If DB inser was successful, login user
            if ($insertedUser) {
                // Login successful: Create a session and redirect to the admin homepage
                $_SESSION['username'] = $user->username;

                //admin.php header() function must be called before any actual output is sent
                // $host  = $_SERVER['HTTP_HOST'];
                // $uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
                // $extra = 'admin.php';
                // header("Location: http://$host$uri/$extra"); //header("Location:https://qpalma.myqnapcloud.com/cms/admin.php");

                header("Location:admin.php");
                // exit;
            } else {
                $results['errorMessage'] = "Niečo sa pokazilo. Skús sa registrovať znova.";
                require(TEMPLATE_PATH . "/admin/registrationForm.php");
            }
        } else {
            // Login failed: display an error message to the user
            $results['errorMessage'] = "Vami zvolené meno je už obsadené.";
            require(TEMPLATE_PATH . "/admin/registrationForm.php");
        }
    } else {
        // We need to create new User, to get new id from it->we fill the id form part with it
        // User has not posted the registration form yet: display the form
        require(TEMPLATE_PATH . "/admin/registrationForm.php");
    }
}

/**
 * User log in, reroutes to loginForm.php
 * */
function login()
{
    $results = array();
    $results['pageTitle'] = $GLOBALS['SITE_NAME'] . " | " . $GLOBALS['LOGIN'];

    $articlesData = Article::getList();
    $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles

    if (isset($_POST['login'])) {

        // User has posted the login form: attempt to log the user in
        $currentUser = User::getByUsername((string) ($_POST['username']));

        $enteredPasswordCheckedInDb = password_verify($_POST['password'], $currentUser->password);

            /*   $passwordEncrypt = password_hash("kolacik", PASSWORD_BCRYPT);
        $passwordChecked = password_verify("kolacik", $spravnyHAsh) */;

        //Password verification-> if entered user exists in db AND password verification passed from db
        if (($_POST['username'] == $currentUser->username) && (password_verify($_POST['password'], $currentUser->password))) {

            // Login successful: Create a session and redirect to the admin homepage
            $_SESSION['username'] = $currentUser->username;

            header("Location:admin.php");
            //header("Location:https://qpalma.myqnapcloud.com/cms/admin.php", true, 301);
            //header() function must be called before any actual output is sent
            //echo("<script>location.href = 'https://qpalma.myqnapcloud.com:8081/cms/admin.php';</script>");
        } else {

            // Login failed: display an error message to the user
            $results['errorMessage'] = $GLOBALS['ERROR10']; //Nesprávne meno alebo heslo
            require(TEMPLATE_PATH . "/admin/loginForm.php");
        }
    } else {
        // User has not posted the login form yet: display the form
        require(TEMPLATE_PATH . "/admin/loginForm.php");
    }
}

/**
 * Logs out the current user(unsets session)
 */
function logout()
{
    unset($_SESSION['username']);
    unset($_POST);
    header("Location:index.php");
}

/**
 * Handles user details and send data into respective page(userChanges.php)
 */
function userDetails()
{
    $results = array();
    $results['pageTitle'] = "Detaily užívateľa";

    $articlesData = Article::getList();
    $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles
    // $data = Article::getArticleByUsername($_SESSION['username']);
    // $results['userArticles'] = $data['results'];
    //get all articles with author == logged user
    $countArticlesUser = Article::getNumOfArticlesByUser($_SESSION['username']);

    //we get info about user-> password
    $currentUserHASHEDPassword = User::getByUsername($_SESSION['username'])->password;
    $currentUserData = User::getByUsername($_SESSION['username']);
    $registeredFrom = $currentUserData->registrationDate;

    //$_POST['userDetailsPosted] == form hidden value=true
    //alternativne nastavime $POST['submitChanges'] z tlacitka submit
    if (isset($_POST['submitChanges'])) {
        $params = array();

        //NO CHANGES MADE AND SEND EMPTY FORM
        if (empty($_POST['username']) && empty($_POST['password'])) {
            $results['errorMessageUsername'] = "Udaje nezmenene.";
            require(TEMPLATE_PATH . "/admin/userChanges.php");
        }

        //USER HAD SET USERNAME//nemoze byt bez [], pretoze by kod bral ze username je zadane='' a zmizlo by z db
        if (!empty($_POST['username'])) {

            $userAlreadyExists = User::getByUsername((string) $_POST['username']);

            // if (empty($_POST['username'])) {
            //   $params['username'] = $currentUserData->username;
            //   $results['errorMessage'] = "Meno nemoze byt praznde.";
            // } else if ($userAlreadyExists && ($currentUserData != $userAlreadyExists)) {
            //funguje //Check if user is not entering current username
            if ($_POST['username'] === $currentUserData->username) {
                $results['errorMessageUsername'] = "Snažíš sa zmeniť si meno na to isté meno?!";
                require(TEMPLATE_PATH . "/admin/userChanges.php");
            }
            //funguje //Check if user with that username already exists
            else if ($userAlreadyExists->username === $_POST['username']) {

                $results['errorMessageUsername'] = "Užívateľ už existuje. Nemožné zmeniť na toto meno.";
                require(TEMPLATE_PATH . "/admin/userChanges.php");
            } else {
                //WRITING NEW NAME INTO VARIABLE
                $params['username'] = $_POST['username'];
                //UPDATING DB with new variable
                $currentUserData->storeFormValues($params);
                $currentUserData->update();
                $_SESSION['username'] = $currentUserData->username;
                //$results['successMessageUsername'] = "Meno zmenené.";

                header("Location:admin.php?action=userDetails&status=changesSaved");
            }
        }

        //USER HAD SET PASSWORD//nemoze byt bez [], pretoze by kod bral ze username je zadane='' a zmizlo by z db
        if (!empty($_POST['password'])) {

            //funguje //Check if user is not entering current password
            $endteredPassword = password_hash($_POST['password'], PASSWORD_BCRYPT);
            if ($endteredPassword == $currentUserHASHEDPassword) {
                $results['errorMessagePassword'] = "Už máš také heslo aké sa snažíš zadať.";
                require(TEMPLATE_PATH . "/admin/userChanges.php");
            } else {

                //WRITING NEW NAME INTO VARIABLE
                $params['password'] = $_POST['password'];
                //UPDATING DB with new variable
                $currentUserData->storeFormValues($params);
                $currentUserData->update();
                // $results['successMessageUsername'] = "Heslo zmenené.";

                header("Location:admin.php?action=userDetails&status=changesSaved");
            }
        }
    } else {
        // User has not posted the userChanges form yet: display the form
        // require(TEMPLATE_PATH . "/admin/userChanges.php");
        //echo "nemenime nic z username a password";
    }

    //USER WANTS TO DELETE HIS IMG, CODE WILL RUN ONLY IF THERE IS IMG ALREADY
    if (!empty($currentUserData->imageExtension)) {
        //$_FILES -> superglobal array that contains all the information about the uploaded image file, "image" must be the same name as in userChanges input name for image.
        //USER CLICKED DELETE IMAGE
        if (isset($_POST['deleteImage'])) {
            $currentUserData->deleteImages();
            $currentUserData->update();
            header("Location:admin.php?action=userDetails&status=imgDeleted");
        }
    }

    //SECOND ALTERNATIVE BUTTON IN OTHER SECTION-HAS SET OTHER 'NAME'
    //USER HAD SET IMG || TEXT ABOUT/OF USER
    if (isset($_POST['submitUserInfoImgChanges'])) {

        //NO CHANGES MADE IN TEXTAREA
        if (empty($_POST['aboutUser'])) {
            $results['errorMessageAboutUser'] = "Text nezmenený.";
            require(TEMPLATE_PATH . "/admin/userChanges.php");
        } else {
            //echo "user pise daco o sebe";
            $params['aboutUser'] = $_POST['aboutUser'];
            $currentUserData->storeFormValues($params); //ulozime data z textarea(vypisane userom) od objektu user
            $currentUserData->update(); //update method of the class User -> rewrites given data to the existing db data.

            header("Location:admin.php?action=userDetails&status=changesSaved");
        }

        //ak existuje natiahnuty obrazok do Files
        if (($_FILES['userImage']['size']) > 0) {
            //echo "obrazok natahujeme!?";

            $currentUserData->storeUploadedImage($_FILES['userImage']); //$_FILES -> superglobal array that contains all the information about the uploaded image file, "image" must be the same name as in editArticle input name for image.
            //update method of the class Article -> rewrites given data to the existing db data.
            $currentUserData->update();
            header("Location:admin.php?action=userDetails&status=changesSaved");
        }
    } else {
        // User has not posted the userChanges form yet: display the form
        //echo "User image posrany :)";
        require(TEMPLATE_PATH . "/admin/userChanges.php");
    }
}

/**
 * Deletes user by user name. Handles user deletion but it DOESNT purge of all recipes
 */
function deleteUser()
{
    $articlesData = Article::getList();
    $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles
    //Submit was pushed
    if (isset($_POST['submitDeleteUser'])) {
        $currentUserData = User::getByUsername($_SESSION['username']); //object or false
        //current user data DOESNT exists in db
        if (!$currentUserData->username) {
            header("Location:admin.php?error=userNotFound");
            return;
        } else { //current user data exists in db
            $currentUserData->deleteUserByUsername();
            header("Location:admin.php?status=userDeleted");

            logout();
        }
    } else {
        // User has not posted the login form yet: display the form
        require(TEMPLATE_PATH . "/admin/userChanges.php");
    }
}

/**
 * Creates new Article class object with parameters from filled Form
 */
function newArticle()
{
    $results = array();
    $results['pageTitle'] = $GLOBALS['SITE_NAME'] . " | " . $GLOBALS['NEW ARTICLE'];

    $results['formAction'] = "newArticle";
    $results['author'] = $_SESSION['username'];

    $articlesData = Article::getList();
    $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles

    if (isset($_POST['saveChanges'])) {

        //PRIDAT RECEPT-> User has posted the article edit form: save the new article
        $article = new Article;
        $article->storeFormValues($_POST);
        $articleKint = $article;
        //d($_FILES);
        $article->insert(); //inserts NEW data to the object Article and to the db
        if (isset($_FILES['image']))
            $article->storeUploadedImage($_FILES['image']);

        header("Location:admin.php?status=changesSaved");
        //d($article);
    } elseif (isset($_POST['cancel'])) {
        // User has cancelled their edits: return to the article list
        header("Location:admin.php");
    } else {
        // User has not posted the article edit form yet: display the form
        $results['article'] = new Article;
        $data = Category::getList(); //storing list of categories later to choose from
        $results['categories'] = $data['results'];
        require(TEMPLATE_PATH . "/admin/editArticle.php");
    }
}

/**
 * Edits Article, Article Id is taken from header action.
 */
function editArticle()
{

    $results = array();
    $results['pageTitle'] = $GLOBALS['SITE_NAME'] . " | " . $GLOBALS['EDIT ARTICLE'];

    $results['formAction'] = "editArticle";

    $articlesData = Article::getList();
    $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles
    // User has posted the article edit form: save the article changes
    if (isset($_POST['saveChanges'])) {

        //If is for check if article already exist
        if (!$article = Article::getById((int) $_POST['articleId'])) {
            header("Location:admin.php?error=articleNotFound");
            return;
        }

        $article->storeFormValues($_POST); //volanie metody pre objekt article
        if (isset($_POST['deleteImage']) && $_POST['deleteImage'] == "yes") {
            $article->deleteImages();
        }
        if (isset($_FILES['image'])){
            $article->storeUploadedImage($_FILES['image']); //$_FILES -> superglobal array that contains all the information about the uploaded image file, "image" must be the same name as in editArticle input name for image.
        }
        $article->update(); //update method of the class Article -> rewrites given data to the existing db data.

        header("Location:admin.php?status=changesSaved");
        
    } elseif (isset($_POST['cancel'])) {

        // User has cancelled their edits: return to the article list
        header("Location:admin.php");
    } else {
        // User has not posted the article edit form yet: display the form
        $results['article'] = Article::getById((int) $_GET['articleId']);
        $isAdmin = User::getByUsername($_SESSION['username'])->role;

        //if user atempting to make changes is author of the article OR is admin
        if (($_SESSION['username'] == $results['article']->author) | $isAdmin == 0) {
            $results['author'] = $results['article']->author;
            $data = Category::getList();
            $results['categories'] = $data['results'];
            require(TEMPLATE_PATH . "/admin/editArticle.php");
        } else {
            echo ('<H2>Unauthorized</H2>');
            //TODO info user has not authorization --> is not author of the article
            //Redirect or alert?
        }
    }
}

/**
 * Deletes article(by ID), article ID it taken from header action
 */
function deleteArticle()
{
    if (!$article = Article::getById((int) $_GET['articleId'])) {
        header("Location:admin.php?error=articleNotFound");
        return;
    } else {
        $isAdmin = User::getByUsername($_SESSION['username'])->role;
        if (($_SESSION['username'] == $article->author) | $isAdmin == 0) {
            $article->deleteImages();
            $article->delete();
            header("Location:admin.php?status=articleDeleted"); //status is used in next refresh of the page, with the status will determine what text will show on the template
        } else {
            echo 'Unathorized - you havent written this article.';
        }
    }
}

/**
 * Shows list of Articles while user is logged in. Basically homepage with added funcionality.
 */
function viewByCategoryLogged()
{
    $results = array();

    // $articleData = Article::getList();
    // $results['articles'] = $articleData['results'];
    // $results['totalRows'] = $articleData['totalRows'];

    $categoryData = Category::getList(); //returns $data['results']
    $results['categories'] = $categoryData['results']; //homepage.php uz si to zobrazi s tohto sama

    $articlesData = Article::getList();
    $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles
    // $results['pageTitle'] = "TeamKK Receptar";
    $results['pageTitle'] = $GLOBALS['SITE_NAME'] . " | " . $GLOBALS['LOGGED'];

    //if there is error or no articles, it will show errormessage
    if (isset($_GET['error'])) {
        if ($_GET['error'] == "articleNotFound")
            $results['errorMessage'] = $GLOBALS['ERROR11']; //"Chyba: recept sa nenašiel."
    }

    //TODO if there is status set(f.ex. newArticle was created)
    if (isset($_GET['status'])) {
        if ($_GET['status'] == "changesSaved")
            $results['statusMessage'] = $GLOBALS['STATUS10']; //"Zmeny boli uložené."
        if ($_GET['status'] == "articleDeleted")
            $results['statusMessage'] = $GLOBALS['STATUS11']; //"Recept bol vymazaný."
    }

    //Search for random Quote from db and store data in $results
    $results['quote'] = Quote::getRandomQuote();

    //calling respective VIEW page
    require(TEMPLATE_PATH . "/homepage.php");
}

/**
 * Shows list of favourites Articles while user is logged in. If no favourites Article exist, there will be te xt/picture information.
 */
function viewFavourites()
{
    $results = array();
    $articlesData = Article::getList();
    $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles
    $results['pageTitle'] = "Obľúbené recepty";
    //TODO
    $favouriteIDs = User::getFavouritesByUsername($_SESSION['username']);
    $favouriteData = Article::getByIds($favouriteIDs); //returns $data['results']
    $results['articles'] = $favouriteData['results'];
    $articlesCount = count($results['articles']);

    //calling respective VIEW page
    require(TEMPLATE_PATH . "/admin/favouriteArticles.php");
}

/**
 * Shows list of Categories while user is logged in. TODO CHANGES
 */
function listCategories()
{
    $results = array();
    $data = Category::getList();
    $results['categories'] = $data['results'];
    $results['totalRows'] = $data['totalRows'];
    $results['pageTitle'] = "Kategórie";

    if (isset($_GET['error'])) {
        if ($_GET['error'] == "categoryNotFound")
            $results['errorMessage'] = "Error: Category not found.";
        if ($_GET['error'] == "categoryContainsArticles")
            $results['errorMessage'] = "Error: Category contains articles. Delete the articles, or assign them to another category, before deleting this category.";
    }

    if (isset($_GET['status'])) {
        if ($_GET['status'] == "changesSaved")
            $results['statusMessage'] = "Your changes have been saved.";
        if ($_GET['status'] == "categoryDeleted")
            $results['statusMessage'] = "Category deleted.";
    }

    require(TEMPLATE_PATH . "/admin/listCategories.php");
}

/**
 * TODO CHANGES
 */
function newCategory()
{

    $results = array();
    $results['pageTitle'] = "Nová kategória";
    $results['formAction'] = "newCategory";

    if (isset($_POST['saveChanges'])) {

        // User has posted the category edit form: save the new category
        $category = new Category;
        $category->storeFormValues($_POST);
        $category->insert();
        header("Location:admin.php?action=listCategories&status=changesSaved");
    } elseif (isset($_POST['cancel'])) {

        // User has cancelled their edits: return to the category list
        header("Location:admin.php?action=listCategories");
    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = new Category;
        require(TEMPLATE_PATH . "/admin/editCategory.php");
    }
}

/**
 * TODO CHANGES
 */
function editCategory()
{

    $results = array();
    $results['pageTitle'] = "Upraviť kategóriu";
    $results['formAction'] = "editCategory";

    // User has posted the category edit form: save the category changes
    if (isset($_POST['saveChanges'])) {

        //if category doesnt exist
        if (!$category = Category::getById((int) $_POST['categoryId'])) {
            header("Location:admin.php?action=listCategories&error=categoryNotFound");
            return;
        }

        $category->storeFormValues($_POST);
        $category->update();
        header("Location:admin.php?action=listCategories&status=changesSaved");
    } elseif (isset($_POST['cancel'])) {

        // User has cancelled their edits: return to the category list
        header("Location:admin.php?action=listCategories");
    } else {

        // User has not posted the category edit form yet: display the form
        $results['category'] = Category::getById((int) $_GET['categoryId']);
        require(TEMPLATE_PATH . "/admin/editCategory.php");
    }
}

/**
 * TODO CHANGES
 */
function deleteCategory()
{

    if (!$category = Category::getById((int) $_GET['categoryId'])) {
        header("Location:admin.php?action=listCategories&error=categoryNotFound");
        return;
    }

    $articles = Article::getList(1000000, $category->id);

    if ($articles['totalRows'] > 0) {
        header("Location:admin.php?action=listCategories&error=categoryContainsArticles");
        return;
    }

    $category->delete();
    header("Location:admin.php?action=listCategories&status=categoryDeleted");
}


function dbManagement()
{
    require(TEMPLATE_PATH . "/dbManagement.php");
}


function dbBackup()
{
    $backupFilePath = time().'sql';
    if (exportDatabase('db_server', DB_USERNAME, DB_PASSWORD, 'id20548436_cms', $backupFilePath )) {
        echo 'db exported';
    } else {
        echo 'error exporting';
    }
}

function dbUpdate()
{

    $targetFilePath   = 'files/yourMysqlBackupFile.sql';

    if (importDatabase('db_server', DB_USERNAME, DB_PASSWORD, 'id20548436_cms', $targetFilePath)) {
        echo 'db updated';
    } else {
        echo 'error importing';
    }
}

function importDatabase($host, $user, $password, $database, $backupFilePath)
{
    //returns true if successfull
    return exec('mysqlimport --host ' . $host . ' --user ' . $user . ' --password ' . $password . ' ' . $database . ' ' . $backupFilePath) === 0;
}

function exportDatabase($host, $user, $password, $database, $targetFilePath)
{
    //returns true if successfull
    return exec('mysqldump --host ' . $host . ' --user ' . $user . ' --password ' . $password . ' ' . $database . ' --result-file=' . $targetFilePath) === 0;
}
