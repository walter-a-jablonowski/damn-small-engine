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

$page  = new WebPage( 'includes/page' );
$layout = $page->newView( 'includes/layout' );
$table = $page->newControl( 'controls/table/view' );

$this->tableClasses   = '';  // no additional classes
$this->headClasses    = '';
$this->headRowClasses = '';
$this->bodyClasses    = '';

// Table header

$headCells = $page->newListView();

foreach( $dbRows[0] as $name => $value )
{
  $headCell = $page->newView( 'controls/table/head_cell' );
  $headCell->content = $value;
  $headCells->addView( $headCell );
}

$table->headContent = $headCells;

// Table lines

$rows = $page->newListView();

foreach( $dbRows as $id => $dbRow )
{
  $row = $page->newView( 'controls/table/row' );
  $cells = $page->newListView();
  
  // Table cells

  $i = 0;
  foreach( $dbRow as $name => $value )
  {
    $i++;

    if( $i == 1 )
      $cell = $page->newView( 'controls/table/first_cell' );  // first cell differs, see https://getbootstrap.com/docs/4.3/content/tables
    else
      $cell = $page->newView( 'controls/table/cell' );

    $cell->content = $value;
    $cells->addView( $cell );
  }
    
  $row->content = $cells;
  $rows->addView( $row );
}

$table->bodyContent = $rows;

$layout->content = $table;
$page->attachContent( $layout );


echo $page->render();

?>
