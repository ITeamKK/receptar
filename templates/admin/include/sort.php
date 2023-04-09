<?php


$articlesToSort = $_REQUEST['articles'];

//SORT


  function sortDescending($a, $b)
  {
    if (strtolower($a->title) == strtolower($b->title)) {
      return 0;
    }
    return (strtolower($a->title) < strtolower($b->title)) ? 1 : -1;
  }

  function sortAscending($a, $b)
  {
    if (strtolower($a->title) == strtolower($b->title)) {
      return 0;
    }
    return (strtolower($a->title) < strtolower($b->title)) ? -1 : 1;
  }


  $new = usort($articlesToSort, "sortAscending");

 // print_r($articlesToSort);

  //d($new);

  echo json_encode($articlesToSort);

  ?>