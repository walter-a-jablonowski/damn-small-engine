<?php

// http://localhost/50-dev-lib-cmn/web/src/php_lib_web/damn-small-engine/sample_normal/view.php

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


define('DEBUG', 'DEBUG');

$env = DEBUG;


// Building a bootstrap 4.3 webpage and table

// Some config

$config = DSEConfig::instance();

if( $env == DEBUG )     $config->preferMinified( false );  // should use minified version ?
elseif( $env == PROD )  $config->preferMinified( true );

$config->setDirPrefix( 'my_' );  // a folder prefix that you can leave out on new View( ... )
// $config->setControlsFolder('controls/');


// Data

$dbRows = [                    // Demo data or load from db
  0 => [
    'col_1' => 'entry 1.1',
    'col_2' => 'entry 1.2'
  ],
  1 => [
    'col_1' => 'entry 2.1',
    'col_2' => null
  ]
];


// Build

$page = new WebPage( 'includes/page' );         // looks like hierarchical identifier, is also: a file path
$layout = $page->newView( 'includes/layout' );  //   prefix and type will be added => my_includes/page.html

// Page data

$layout->myValue  = 'My dynamic content 1';
$layout->myValue2 = 'My dynamic content 2';

// Table

$table = $page->newSimpleControl( 'controls/table/view' );  // column headings are hard coded see my_controls/table/view.html
$rows = $page->newListView();        // instead you could use ListView::buildList( ... );
                                     //   for the whole table, see basic sample

foreach( $dbRows as $id => $dbRow )  // if you prefer, you also could use a for loop in
{                                    //   html instead, see "misc samples"
  $row = $page->newView( 'controls/table/row' );

  $row->field1 = $dbRow['col_1'];    // you could also use: $row->setValues( $dbRow );
  $row->field2 = $dbRow['col_2'];
  
  $rows->addView( $row );
}

$table->addSubView( 'content', $rows );
$layout->addSubView( 'table', $table );
$page->attachContent( $layout );


echo $page->render();

?>
