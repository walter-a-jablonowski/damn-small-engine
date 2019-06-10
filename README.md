# Damn Small Engine

**PHP low code templating system - small but powerful**

Visit my personal homepage: https://walter-a-jablonowski.github.io

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

This was tested using PHP 7.1.9, it should work from 5.3 and above.

A simple PHP templating system, based on an idea that I saw somewhere on the internet about 2 years ago. Basically, this uses PHP's output buffering and magic methods. It is a truly awesome concept, because the code is so tiny compared 2 popular templating systems. Use less code, achive more! You can easily read that small code and modify it for your needs. I improved the basic idea and added some features. Have a look at the very small classes View and ListView files in /src. These are enough (shown in [Basic sample](Basic_sample.md)), although there are more classes providing features: WebPage and Control (see samples below).

If you like run the sample code in /sample_normal, /sample_advanced and /sample_basic.

```
composer require walter-a-jablonowski/damn-small-engine
```

## Features

* **Just 2 small classes** (can easily be modified), 2 additional classes providing features
* **Build nested views** and/or lists with data
* **Add styles/js** in a `WebPage`
* **Automatically import** component specific styles and js in a web page
  * just add your html block in `WebPage`, the rest will be done by this lib
* This lib is like `$view->subView = new ListView( ... )` (or variations of it)
* **All logic in code!** Template files don't have any control structures (as it should be)
  * Why the heck do people invent a new programming lang in their templating systems while they already have one?!
* You don't have 2 learn a new syntax => **no problems fixing syntax bugs**
  * just use PHP's syntax that you know well, you can easily compose your view
* ... and **all PHP language features** are available

**Can't get any updates?** ... for your prefered templating system anymore? Does it have massive codes, maintainance impossibile? No problem with Damn Small Engine. This thing is so small, you can easily understand the code and maintain or extend it yourself.

## Normal sample

**Building a page and bootstrap 4.3 table**

:grey_exclamation: There is also a much [simpler sample](Basic_sample.md), that used only the most basic 2 classes and also has some additional usage features. You can find a more **complex sample** below. See API for more info **(currently missing)**.

* **Run the code:** /sample_normal/view.php
* **HTML code see:** /sample_normal/my_controls and /sample_normal/my_includes

```php
// Some config

if( $env == DEBUG )     WebPage::preferMinified( false );  // should use minified version ?
elseif( $env == PROD )  WebPage::preferMinified( true );

// Control::setControlsFolder('controls/');
WebPage::setDirPrefix( 'my_' );  // a folder prefix that you can leave out on new View( ... )


// Data

$dbRows = ...


// Build

$page = new WebPage( 'includes/page' );         // looks like hierachical identifier, is also: a file path
$layout = $page->newView( 'includes/layout' );  //   prefix and type will be added => my_includes/page.html

// Page data

$layout->myValue  = 'myString';
$layout->myValue2 = 'myString 2';

// Table

$table = $page->newControl( 'controls/table/view' );
$rows = $page->newListView();  // instead you may use ListView::buildList( ... );
                               // see basic sample

foreach( $dbRows as $id => $dbRow )
{
  $row = $page->newView( 'controls/table/row' );

  $row->field1 = $dbRow['field 1'];  // you could also use: $row->setValues( $dbRow );
  $row->field2 = $dbRow['field 2'];
  
  $rows->addView( $row );
}

$table->content = $rows;
$layout->table = $table;
$page->attachContent( $layout );


echo $page->render();
```

## Advanced sample

**... and building a bootstrap 4.3 table dynamically** (for every database table)

**still debugging this one**

This sample also adds some style and classes, and uses a "component", a html block that needs special style and js files. These will be automatically included. See API for more info **(currently missing)**.

* **Run the code:** /sample_advanced/view.php
* **HTML code see:** /sample_advanced/my_controls and /sample_advanced/my_includes

```php
// Some config

if( $env == DEBUG )     WebPage::preferMinified( false );  // should use minified version ?
elseif( $env == PROD )  WebPage::preferMinified( true );

// Control::setControlsFolder('controls/');
WebPage::setDirPrefix( 'my_' );  // a folder prefix that you can leave out on new View( ... )


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


// Component

// just add your view, the lib will add needed style and js for the component

$comp = $page->newComponent( 'components/demo_comp' );
//
// this will also include
//
//        my_components/demo_comp/style.css  =>  page head <style></style>
//   and  my_components/demo_comp/code.js    =>  page <script></script>
//   and  my_components/demo_comp/style_includes/dummy.css
//   and  my_components/demo_comp/style_includes/dummy2.css
//   and  my_components/demo_comp/js_includes/dummy.js
//
// have a look at my_includes/layout.html and see where

$comp->content  = 'I am a component';
$layout->myComponent = $comp;


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


echo $page->render();
```

## LICENSE

Copyright (C) Walter A. Jablonowski 2018-2019, MIT [License](LICENSE)
