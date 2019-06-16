# Damn Small Engine

**PHP low code templating system - small but powerful**


## Advanced sample

**... and building a bootstrap 4.3 table dynamically** (for every database table)

This sample also includes some style and adds classes dynamically.

* **Run the code:** /sample_advanced/view.php
* **HTML code see:** /sample_advanced/my_includes and /sample_advanced/my_controls

```php
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

$page = new WebPage( 'includes/page' );
$layout = $page->newView( 'includes/layout' );


// Add some style dynamically (or do in html)

$page->addStyleInclude( 'includes/styles/add_style.css' );  // just a dummy file with a comment (for demo)
$page->addStyle( 'body { font-size: 15px; }' );             // => page head <style></style>
// the same for js use: addJSInclude() addJS()


// Add some classes dynamically (just for fun)

$layout->h1Classes = "mt-4 mb-3";  // prints class="mt-4 mb-3" cause printClass() used in my_includes/layout.html
                                   // see layout.html, use View's

$layout->funClasses = "some fun classes";  // just for fun, see how addClasses() is used in my_includes/layout.html


// Add content

$layout->myValue  = 'My dynamic content 1';
$layout->myValue2 = 'My dynamic content 2';


// Table (dynamically)

$table = $page->newSimpleControl( 'controls/table/view' );


// Table header

$colNames = ['Column 1', 'Column 2'];  // Non-DB-Names for UI, load from somewhere ...

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
$layout->addSubView( 'table', $table );
$page->attachContent( $layout );


echo $page->render();
```

## LICENSE

Copyright (C) Walter A. Jablonowski 2018-2019, MIT [License](LICENSE)


[Privacy](https://walter-a-jablonowski.github.io/privacy.html) | [Legal](https://walter-a-jablonowski.github.io/imprint.html)