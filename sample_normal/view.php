<?php

// http://localhost/50-dev-lib-cmn/web/src/php_lib_web/damn-small-engine/sample_advanced/view.php

use WAJ\Lib\Web\DamnSmallEngine\View;
use WAJ\Lib\Web\DamnSmallEngine\ListView;
use WAJ\Lib\Web\DamnSmallEngine\WebPage;
use WAJ\Lib\Web\DamnSmallEngine\Control;

require '../src/ViewBase.php';
require '../src/View.php';
require '../src/ListView.php';
require '../src/WebPage.php';
require '../src/Control.php';


define('DEBUG', 'DEBUG');

$env = DEBUG;


// Building a bootstrap 4.3 table, see https://getbootstrap.com/docs/4.3/content/tables

// Some config

if( $env == DEBUG )     WebPage::preferMinified( false );  // should use minified version ?
elseif( $env == PROD )  WebPage::preferMinified( true );

// Control::setControlsFolder('controls/');
WebPage::setDirPrefix( 'my_' );


// Data

$dbRows = [                    // Demo data or load from db
  'row 1' => [
    'field 1' => 'entry 1.1',
    'field 2' => 'entry 1.2'
  ],
  'row 2' => [
    'field 1' => 'entry 2.1',
    'field 2' => null
  ]
];


// Build

$page = new WebPage( 'includes/page' );
$layout = $page->newView( 'includes/layout' );

// Page data

$layout->myValue  = 'myString';
$layout->myValue2 = 'myString 2';

// Table

$table = $page->newControl( 'controls/table/view' );
$rows = $page->newListView();

foreach( $dbRows as $id => $dbRow )
{
  $row = $page->newView( 'controls/table/row' );

  $row->field1 = $dbRow['field 1'];
  $row->field2 = $dbRow['field 2'];
  
  $rows->addView( $row );
}

$table->content = $rows;
$layout->table = $table;
$page->attachContent( $layout );


echo $page->render();

?>
