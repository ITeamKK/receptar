<?php
//This code is for the handling RESPONSE for AJAX REQUEST to dynamically change Class to the heart icon(to change image)
session_start();
require_once("../../../config.php");
// require_once("/var/www/html/cms/classes/User.php"); //tiez funguje absolute path-zacina /
require_once("../../../classes/User.php");	//relative path od tohto suboru

// we get the current user data
$currentUserData = User::getByUsername($_SESSION['username']);

// we get the article ID which we want to add/delete from fav list
$articleID = $_REQUEST['articleID'];

//if there is something in favouriteArticles = fav # is there
if ($currentUserData->favouriteArticles) {

	// already existing fav article IDs are put into an array or if it was empty, array == false
	$array = explode(",", $currentUserData->favouriteArticles);

	//we filter the array to get rid of empty strings
	$filteredArray = array_filter($array);

	// if the ID was already in the array and we want to delete it
	if (($index = array_search($articleID, $filteredArray)) !== false) {
		unset($filteredArray[$index]);
	} else {
		// the article ID has to be added to favourites array
		array_push($filteredArray, $articleID);
	}
	// we pass the modified array to $params
	$params['favouriteArticles'] = implode(",", $filteredArray);
} else {
	//if there is no fav article(null) in favouriteArticles, we will add number of our fav article
	$params['favouriteArticles'] = ($articleID . ",");
}

//UPDATING DB with new variable7
$currentUserData->storeFormValues($params);
$currentUserData->update();

//if favourite article exist, we return to AJAX RESPONSE text => 'fas', if not => 'far'
$favouritesExist = User::getFavouritesByUsername($_SESSION['username']);
if($favouritesExist){
	$class = 'fas';
}else{
	$class = 'far';
};
//Send out responseText in JSON
echo json_encode($class);

// var_dump($favouritesExist);

?>

