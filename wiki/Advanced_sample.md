# Damn Small Engine

**PHP low code templating system - small but powerful**


## Advanced sample

**... and building a bootstrap 4.3 table dynamically** (for every database table)

**still debugging this one**

This sample also adds some style and classes, and uses a "component", whock is a html block that needs special style and js files. These will be automatically included. See also class comments in /src (API desc currently missing).

* **Run the code:** /sample_advanced/view.php
* **HTML code see:** /sample_advanced/my_controls and /sample_advanced/my_includes

```php
// Some config

$config = DSEConfig::instance();

if( $env == DEBUG )     $config->preferMinified( false );  // should use minified version ?
elseif( $env == PROD )  $config->preferMinified( true );

// $config->setControlsFolder('controls/');
$config->setDirPrefix( 'my_' );  // a folder prefix that you can leave out on new View( ... )


// Data

$dbRows = ...


// Build

$page = new WebPage( 'includes/page' );
$layout = $page->newView( 'includes/layout' );


// Add some style dynamically (or do in html)

$page->addStyleInclude( 'includes/styles/style.css' );
$page->addStyle( 'body { font-size: 15px; }' );  // => page head <style></style>

// the same for js use: addJSInclude() addJS()


// Add some classes dynamically

$layout->h1Classes = "some classes";
// see my_includes/layout.html, use View's printClasses() or addClasses()


// Table (dynamically)

$table = $page->newSimpleControl( 'controls/table/view' );

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
```

## LICENSE

Copyright (C) Walter A. Jablonowski 2018-2019, MIT [License](LICENSE)


[Privacy](https://walter-a-jablonowski.github.io/privacy.html) | [Legal](https://walter-a-jablonowski.github.io/imprint.html)