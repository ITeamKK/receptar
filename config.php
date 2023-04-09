<?php
//prvy riadok musi byt vzdy <?php az potom komenty
// This file contain CLASS PATHS, GLOBAL VARIABLES, ACCESS INFORMATION, and EXCEPTION FUNCTIONS


include("globals.php"); //defined global variables
ini_set( "display_errors", true ); //line causes error messages to be displayed in the browser,it should be set to false on a live site since it can be a security risk.
date_default_timezone_set( "Europe/Madrid" );  //we need to tell PHP our server’s timezone (otherwise PHP generates a warning message) http://www.php.net/manual/en/timezones.php

// config db according to server
if(strpos($_SERVER['SERVER_NAME'], '000webhostapp')){
  //DB ACCESS
define( "DB_DSN", "mysql:host=localhost;dbname=id20548436_cms;charset=utf8mb4"); //where to find our MySQL database + Username and Password in next 2 rows

//TEST SLOVAK ACCES TO DB
define( "DB_DS_SLOVAK", "mysql:host=localhost;dbname=id20548436_cms;charset=utf8mb4");
//charset is necessary to set to UTF8mb4 for slovak characters?

}else{
//DB ACCESS
define( "DB_DSN", "mysql:host=db_server;dbname=id20548436_cms;charset=utf8mb4"); //where to find our MySQL database + Username and Password in next 2 rows

//TEST SLOVAK ACCES TO DB
define( "DB_DS_SLOVAK", "mysql:host=db_server;dbname=id20548436_cms;charset=utf8mb4");
//charset is necessary to set to UTF8mb4 for slovak characters?
}

define( "DB_USERNAME", "id20548436_root" );
define( "DB_PASSWORD", "qc0[Pa|mT7m01)hG" );
/* define( "DB_CHARSET", "charset=utf8" );*/

define( "CLASS_PATH", "classes" ); //relative path to the class files
define( "TEMPLATE_PATH", "templates" ); //relative path where our script should look for the HTML template files
define( "HOMEPAGE_NUM_ARTICLES", 1000 );//controls the maximum number of article headlines to display on the site homepage

//ADMIN STUFF
define( "ADMIN_USERNAME", "admin" );
define( "ADMIN_PASSWORD", "20domacnost20" );

//IMG STUFF
define( "ARTICLE_IMAGE_PATH", "assets/images/articles" ); //path to the article image folder, RELATIVE
define( "IMG_TYPE_FULLSIZE", "fullsize" );
define( "IMG_TYPE_THUMB", "thumb" );
define( "ARTICLE_THUMB_WIDTH", 240 ); //width in pixels, will be generated during upload (default:120)
define( "JPEG_QUALITY", 85 ); //0-worst/100-best quality/size
//USER IMGs
define( "USER_IMAGE_PATH", "assets/images/users" ); //path to the artcle image folder, RELATIVE
define( "USER_THUMB_WIDTH", 120 ); //width in pixels, will be generated during upload (default:120)

//CLASS PATHS
require( CLASS_PATH . "/Article.php" );
require( CLASS_PATH . "/Category.php" );
require( CLASS_PATH . "/Quote.php" );
require( CLASS_PATH . "/User.php" );
require( CLASS_PATH . "/Security.php" );

//EXCEPTIONS
function handleException( $exception ) { //simple function to handle any PHP exceptions that might be raised as our code runs
  echo "Vyskytla sa chyba, skús šťastie neskôr. ";  //generic error message instead of full list of errors displayed to the user
  echo ( $exception->getMessage() );
  error_log( $exception->getMessage() );
}

set_exception_handler( 'handleException' ); //calling the function when exception handling is needed
//DISPLAY ERRORS https://www.php.net/manual/en/ini.list.php
 ini_set('display_errors', 1);
 ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);

//ADDITIONAL
define("TODAY",date('F j, Y'));
?>
