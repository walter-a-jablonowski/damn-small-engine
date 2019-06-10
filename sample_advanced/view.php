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

require 'kint.phar';


define('DEBUG', 'DEBUG');

$env = DEBUG;


// Building a bootstrap 4.3 table dynamically (for every database table)

// This sample also adds style 2 page, and uses a "component", a html block that needs special style and js files. These will be automatically included. See API for more info **(currently missing)**.


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


// Add some style (the same for js use

$page->addStyleInclude( 'includes/styles/style.css' );
$page->addStyle( 'font-size; 15px;' ); // => page head <style></style>

// the same for js use: addJSInclude() addJS()


// Component

// just add your view, the lib will add needed style and js for the component

$comp = $page->newComponent( 'components/demo_comp' );
//
// this will also include  my_components/demo_comp/style.css => page head <style></style>
//                    and  my_components/demo_comp/code.js   => page <script></script>
//                    and  my_components/demo_comp/style_includes/dummy.css
//                    and  my_components/demo_comp/js_includes/dummy.js
//
// have a look at my_includes/layout.html 2 see where

$comp->myValue  = 'I am a component';
$layout->component = $comp;  


// Table (dynamically)

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


// !d( $page );

echo $page->render();

?>
