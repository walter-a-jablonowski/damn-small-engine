<?php

// http://localhost/50-dev-lib-cmn/web/src/php_lib_web/damn-small-engine/sample_basic/view.php

use WAJ\Lib\Web\DamnSmallEngine\View;
use WAJ\Lib\Web\DamnSmallEngine\ListView;

require '../src/ViewBase.php';
require '../src/View.php';
require '../src/ListView.php';


// Demo data

$listData = [                     // or load from db
  'row 1' => [
    'field 1' => 'entry 1.1',
    'field 2' => 'entry 1.2'
  ],
  'row 2' => [
    'field 1' => 'entry 2.1',
    'field 2' => 'entry 2.2'
  ]
];


// Make a view, add values            // You could add View( ..., View::ESCAPE_ALL_VALUES ) which
                                      //   is htmlspecialchars() for all added values, or do it yourself
$view = new View( 'includes/main' );  // The '.html' will be added => 'includes/main.html'
$view->myVal = 'sample value';        // Just add what you need
// $view->myVal2 = ...
// $view->myVal2 = View::escape( $myStr );  // Escape helper
                                  

// Add a list

// Version 1

$listView = ListView::buildList( 'includes/list1_entry', $listData );
$view->list1 = $listView;


// Version 2

$listView = new ListView();         // The sample above is just this code packed in a static method
                                    // you might want use this version if you need 2 make something special
// /*
foreach( $listData as $rowValues )
{
  $entryView = new View( 'includes/list1_entry' );
  $entryView->setValues( $rowValues );

  $listView->addView( $entryView );
}
// */

$view->list2 = $listView;


// Alternative version

$list5 = new View( 'includes/list2_entries' );
$list5->setValues( $listData );
$view->list5 = $list5;


// You could also add: views - in a list - in a view - in a list

$outerList = new ListView();

for( $i=0; $i < 2; $i++ )
{
  $entryView = new View( 'includes/table' );
  $innerList = ListView::buildList( 'includes/list1_entry', $listData );
  $entryView->list = $innerList;
  
  $outerList->addView( $entryView );
}

$view->listInList = $outerList;


// Build all

echo $view;

?>