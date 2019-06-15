<?php

// http://localhost/50-dev-lib-cmn/web/src/php_lib_web/damn-small-engine/sample_basic/view.php

use WAJ\Lib\Web\DamnSmallEngine\ViewBase;
use WAJ\Lib\Web\DamnSmallEngine\View;
use WAJ\Lib\Web\DamnSmallEngine\ListView;
use WAJ\Lib\Web\DamnSmallEngine\WebPage;
use WAJ\Lib\Web\DamnSmallEngine\SimpleControl;
use WAJ\Lib\Web\DamnSmallEngine\DSEConfig;

require '../src/ViewBase.php';
require '../src/View.php';
require '../src/ListView.php';
require '../src/WebPage.php';
require '../src/SimpleControl.php';
require '../src/DSEConfig.php';


// Some config

$config = DSEConfig::instance();

if( $env == DEBUG )     $config->preferMinified( false );  // should use minified version ?
elseif( $env == PROD )  $config->preferMinified( true );

$config->setDirPrefix( 'my_' );  // a folder prefix that you can leave out on new View( ... )
// $config->setControlsFolder('controls/');


// Demo data

$listData = [                     // or load from db
  0 => [
    'col_1' => 'entry 1.1',
    'col_2' => 'entry 1.2'
  ],
  1 => [
    'col_1' => 'entry 2.1',
    'col_2' => 'entry 2.2'
  ]
];


// Make a view, add values            // You could add View( ..., DSEConfig::ESCAPE_ALL_VALUES ) which
                                      //   is htmlspecialchars() for all added values, or do it yourself
$view = new View( 'includes/main' );  // The '.html' will be added => 'includes/main.html'
$view->myVal = 'sample value';        // Just add what you need
// $view->myVal2 = ...
// $view->myVal2 = View::escape( $myStr );  // Escape helper
                                  

// Add a list

// Version 1

$listView = ListView::buildList( 'includes/list_entry', $listData );
$view->list1 = $listView;


// Version 2

$listView = new ListView();         // The sample above is just this code packed in a static method
                                    // you might want use this version if you need 2 make something special
// /*
foreach( $listData as $rowValues )
{
  $entryView = new View( 'includes/list_entry' );
  $entryView->setValues( $rowValues );

  $listView->addView( $entryView );
}
// */

$view->list2 = $listView;


// You could also add: views - in a list - in a view - in a list

$outerList = new ListView();

for( $i=0; $i < 2; $i++ )
{
  $entryView = new View( 'includes/table' );
  $innerList = ListView::buildList( 'includes/list_entry', $listData );
  $entryView->list = $innerList;
  
  $outerList->addView( $entryView );
}

$view->listInList = $outerList;


// Build all

echo $view;

?>