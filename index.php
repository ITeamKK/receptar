<?php
require("config.php");         //definujeme zakladne globalne premenne a cesty
require("kint.phar");          //debugging

//Kint::dump($GLOBALS, $_SERVER, "Hello World");
$action = isset($_GET['action']) ? $_GET['action'] : ""; //ak nenajde ziadnu akciu= za lomitkom definovanu rutu, tak zadame ze je action=empty a nasledujuci switch odkaze na homepage.php

switch ($action) {
  case 'archive':
    archive();  //http://localhost/cms/?action=archive
    break;
  case 'viewArticle':
    viewArticle();  //http://localhost/cms/?action=viewArticle&articleId=26
    break;
  case 'viewCategory':
    viewByCategory(); //http://localhost/cms/?action=viewCategory
    break;
  case 'searchForArticle':
    showSearchResults(); //http://localhost/cms/index.php?action=searchForArticle&q=
    break;
  case 'policy':
    showPrivacyPolicy(); //http://localhost/cms/?action=policy
    break;
  case 'contactForm':
    viewContactForm(); //http://localhost/cms/?action=contactForm
    break;
  case 'aboutUs':
    viewAboutUs(); //http://localhost/cms/?action=aboutUs
    break;
  default:
    homepage(); //http://localhost/cms/
}


/**
 * returns true if logged user is admin | false if not
 **/
function checkAdmin()
{
  $admin = (User::getByUsername($_SESSION['username'])->role == 0) ? true : false;
  return $admin;
}


/**
 * Returnes all Articles and reroutes to archive.php
 **/
function archive()
{
  $results = array();
  $categoryId = (isset($_GET['categoryId']) && $_GET['categoryId']) ? (int) $_GET['categoryId'] : null;

  //returns a category object matching the given category ID, if no id given, the Category object is created with 0 rows
  $results['category'] = Category::getById($categoryId);

  //returns array - number of articles in DB and total article rows
  $data = Article::getList(100000, $results['category'] ? $results['category']->id : null);

  $results['articles'] = $data['results']; //uses archive.php
  $results['totalRows'] = $data['totalRows']; //uses archive.php

  //Returns all (or a range of) Category objects in the DB
  $data = Category::getList();
  $results['categories'] = array();
  foreach ($data['results'] as $category) $results['categories'][$category->id] = $category;

  //archive.php uses this
  //if exist category => the page Heading will be category name, if not ==> Global variable?
  $results['pageHeading'] = $results['category'] ?  $results['category']->name : $GLOBALS['ARCHIVE'];
  $results['pageTitle'] = $GLOBALS['SITE_NAME'] . " | " . $results['pageHeading'];
  require(TEMPLATE_PATH . "/archive.php");
}

/**
 * returns specific Article by selected ID(previously selected in Category View->viewArticles.php)
 **/
function viewArticle()
{
  if (!isset($_GET["articleId"]) || !$_GET["articleId"]) {   //ak v rute neexistuje "articleId" ide spat do viewArticles
    homepage();
    return;
  }

  $results = array();
  $results['article'] = Article::getById((int) $_GET["articleId"]);
  $results['previousArticle'] = Article::getPreviousArticleId((int) $_GET["articleId"]);
  $results['nextArticle'] = Article::getNextArticleId((int) $_GET["articleId"]);
  $results['category'] = Category::getById($results['article']->categoryId);
  $results['pageTitle'] = $GLOBALS['SITE_NAME'] . " | " . $results['article']->title;
  $article = $results['article']; //k comu sluzi?

  //Get the author of the article(object|null)
  $authorName = $results['article']->author;
  $objAuthor = User::getByUsername((string)$authorName); //if exist->object | null
  if(isset($objAuthor)){
    $userImagePath = $objAuthor->getImagePath(); //return img location to show
    // $userInfo = $objAuthor->aboutUser; //return userInfo from DB
  }else{
    $userImagePath = false; //article doesnt show user image nor user info
  };

  $articlesData = Article::getList();
  $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles

  require(TEMPLATE_PATH . "/viewArticle.php");
}



/**
 * returns all Articles by selected Category
 **/
function viewByCategory()
{
  if (!isset($_GET["idCategory"]) || !$_GET["idCategory"]) {
    homepage();
    return;
  }

  $results = array();
  $data = Article::getArticleByCat($_GET["idCategory"]);
  $getCatName = Category::getById($_GET["idCategory"]); //returns whole object

  $results['articles'] = $data['results'];

  //$results['categoryTitle'] = "ReceptyKategoria"; //? neviem co to robi
  $results['pageHeading'] = $GLOBALS['CATEGORY'];
  $results['pageSubHeading'] = $GLOBALS['SUBCATEGORY'];
  $results['categoryName'] = $getCatName->name; //should fetch name from category Object
  $results['pageTitle'] = $GLOBALS['SITE_NAME'] . " | " . $GLOBALS['CATEGORY'] . " | ". $results['categoryName'];

  $articlesData = Article::getList();
  $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles

  require(TEMPLATE_PATH . "/viewArticles.php");
}



/**
 * returns landing page whethever is user logged in or not?
 **/
function homepage()
{
  $results = array();

  $data = Article::getList(HOMEPAGE_NUM_ARTICLES);  //urobi zoznam vsetkych articles z DB momentalne je zadefinovane len 1000!
  $results['articles'] = $data['results'];
  $results['totalRows'] = $data['totalRows'];

  //Search for Categories and store data in $results
  $data = Category::getList(); //returns $data['results']
  $results['categories'] = $data['results'];

  //$results['pageTitle'] = "TeamKK Receptar"; //OBSOLETE by global variable
  $results['pageTitle'] = $GLOBALS['SITE_NAME'];

  //DELETE, NOT IN USE ANYMORE? When user is not logged in
  $results['loggedUser'] = false;

  //Search for random Quote from db and store data in $results
  $results['quote'] = $quoteData = Quote::getRandomQuote();

  //calling respective VIEW page
  require(TEMPLATE_PATH . "/homepage.php");

  /* DELETE
  $result2 = array();
  $data = Article::getArticleByCat(6);
  $articleCount = Article::getNumOfArticleByCat(6);
  $result2['x'] = $data['results'];
  d($data,$data['results'],$articleCount);
  */
}


/**
 * returns searched results from searbox / user input?
 **/
function showSearchResults()
{
  //when there is no question/empty question sent, return to homepage
  if (!isset($_GET["q"]) || !$_GET["q"]) {
    homepage();
    return;
  }

  $results = array();
  $search = array();
  //write GET to the array q
  $search['q'] = $_GET["q"];

  //?used in class Article for search purposes
  $search['s'] = '0';
  //?used in class Article for search purposes
  $search['n'] = '10';

  $data = Article::searchArticles($search);

  $results['articles'] = $data['results'];
  $articlesCount = count($results['articles']);

  $results['pageTitle'] =  $GLOBALS['SITE_NAME'] . " | " . $GLOBALS['SEARCH RESULTS'] ;

  $articlesData = Article::getList();
  $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles

  require(TEMPLATE_PATH . "/viewArticles.php");
}



/**
 * returns Privacy Policy page
 **/
function showPrivacyPolicy()
{

  $results['pageTitle'] =  $GLOBALS['SITE_NAME'] . " | " . $GLOBALS['PRIVACY POLICY'];
  $articlesData = Article::getList();
  $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles

  require(TEMPLATE_PATH . "/include/viewPrivacyPolicy.php");
}


/**
 * Displays contact form and handles sending form data out to mailbox
 **/
function viewContactForm()
{
  $results['pageTitle'] = $GLOBALS['SITE_NAME'] . " | " . $GLOBALS['CONTACT FORM'];
  $articlesData = Article::getList();
  $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles

  // User has posted the contact form: attempt to send data
  if (isset($_POST['submitContactForm'])) {
  } else {
    //user has NOT yet posted the form: show contactForm
    require(TEMPLATE_PATH . "/include/contactForm.php");
  }
}


/**
 * Displays contact form and handles sending form data out to mailbox
 **/
function viewAboutUs()
{
  $results['pageTitle'] = $GLOBALS['SITE_NAME'] . " | " . $GLOBALS['ABOUT US'];
  $articlesData = Article::getList();
  $results['totalRows'] = $articlesData['totalRows']; //for showing total # of articles


  //show aboutUs.php
  require(TEMPLATE_PATH . "/include/aboutUs.php");
}

