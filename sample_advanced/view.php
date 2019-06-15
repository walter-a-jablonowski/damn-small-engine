<?php

// http://localhost/50-dev-lib-cmn/web/src/php_lib_web/damn-small-engine/sample_advanced/view.php

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

require 'kint.phar';


define('DEBUG', 'DEBUG');

$env = DEBUG;


// Advanced sample and Building a bootstrap 4.3 table dynamically (for every database table)

// This sample also adds style 2 page, and uses a "component", a html block that needs special style and js files. These will be automatically included. See API for more info **(currently missing)**.


// Some config

$config = DSEConfig::instance();

if( $env == DEBUG )     $config->preferMinified( false );  // should use minified version ?
elseif( $env == PROD )  $config->preferMinified( true );

// $config->setControlsFolder('controls/');
$config->setDirPrefix( 'my_' );  // a folder prefix that you can leave out on new View( ... )


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

$page = new WebPage( 'includes/page' );
$layout = $page->newView( 'includes/layout' );


// Add some style dynamically (or do in html)

// $page->addStyleInclude( 'includes/styles/style.css' );                                 // Task:
// $page->addStyle( 'body { font-size: 15px; }' );  // => page head <style></style>

// the same for js use: addJSInclude() addJS()


// Add some classes dynamically

// $layout->h1Classes = "some classes";
// see my_includes/layout.html, use View's printClasses() or addClasses()


// Table (dynamically)

$table = $page->newSimpleControl( 'controls/table/view' );

// $table->tableClasses   = '';  // no additional classes
// $table->headClasses    = '';
// $table->headRowClasses = '';
// $table->bodyClasses    = '';

// Table header

$colNames = ['Column 1', 'Column 2'];

$headCells = $page->newListView();

foreach( $colNames as $name => $value )
{
  $headCell = $page->newView( 'controls/table/head_cell' );
  $headCell->content = $value;
  $headCells->addView( $headCell );
}

$table->addSubView( 'headContent', $headCells );

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

    $cell->addSubView( 'content', $value );
    $cells->addView( $cell );
  }
    
  $row->addSubView( 'content', $cells );
  $rows->addView( $row );
}

$table->addSubView( 'bodyContent', $rows );
$layout->addSubView( 'content', $table );
$page->attachContent( $layout );


// !d( $page ); 
// var_dump( $page ); 

echo $page->render();

?>
