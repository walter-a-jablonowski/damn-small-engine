# Damn Small Engine

**PHP low code templating system - small but powerful**

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

Version 0.5 - This was tested using PHP 7.1.9, should run at leat on 7.1.9 and above.
**Some samples still need debuging**

A simple PHP templating system, based on an idea that I saw somewhere on the internet about 2 years ago. Basically, this uses PHP's output buffering and magic methods. It is a truly awesome concept, because the code is so tiny compared 2 popular templating systems. Use less code, achive more! You can easily read that small code and modify it for your needs. I improved the basic idea and added some features. Have a look at the very small classes View and ListView files in /src. These are enough (shown in [Basic sample](wiki/Basic_sample.md)), although there are more classes providing features: WebPage and Control ([see samples below](https://github.com/walter-a-jablonowski/damn-small-engine#normal-sample)).

If you like run the sample code in /sample_normal, /sample_advanced and /sample_basic.

```
composer require walter-a-jablonowski/damn-small-engine
```

**Table of contents**

* [Compare it](https://github.com/walter-a-jablonowski/damn-small-engine#compare-it)
* [Features](https://github.com/walter-a-jablonowski/damn-small-engine#features)
* [Normal sample](https://github.com/walter-a-jablonowski/damn-small-engine#normal-sample)
  * also includes samples overview
* [Classes overview](https://github.com/walter-a-jablonowski/damn-small-engine#classes)
* [License](https://github.com/walter-a-jablonowski/damn-small-engine#license)

> If you like visit my personal homepage: [walter-a-jablonowski.github.io](https://walter-a-jablonowski.github.io)

## Compare Damn Small Engine

... with popular templating systems:

* [Mustache PHP](https://github.com/bobthecow/mustache.php) (free) - Unusual syntax, could require some learning - but I like that class mapping feature. All logic in code, that's nice. The engine code seems too large, should be maintainable (just in case there are no updates).
* [Laravel Blade](https://laravel.com/docs/5.8/blade) (free) - Again: I like some of their features, but I'm unsure if this is really needed. See how much code they [are using](https://github.com/laravel/framework/tree/5.8/src/Illuminate/View) and compare 2 [Damn Small Engine's few classes](src/).

## Features

* **Small:** Just 2 small classes for basic use (can easily be modified)
* additional classes providing more features (small compared 2 third party engines)
* **Build nested views** and/or lists with data
* **Add styles/js** in a `WebPage`
* **Automatically import** component specific styles and js in a web page
  * just add your html block in `WebPage`, the rest will be done by this lib
* This lib is like `$view->subView = new ListView( ... )` (or variations of it)
* **All logic in code!** Template files don't need any control structures (as it should be)
  * Why the heck do people invent a new programming lang in their templating systems while they already have one?!
  * In fact logic in templates ***is possible***, see [misc samples](wiki/Misc_samples.md) (no recommendation)
* You don't have 2 learn a new syntax => **no problems fixing syntax bugs**
  * just use PHP's syntax that you know well, you can easily compose your view
* ... and **all PHP language features** are available

**Can't get any updates?** ... for your preferred templating system anymore? Does it have massive codes, maintenance impossible? No problem with Damn Small Engine. This thing is so small, you can easily understand the code and maintain or extend it yourself.

## Normal sample

**Building a bootstrap 4.3 webpage and table**

:grey_exclamation: There are more samples available

* A much [simpler sample](wiki/Basic_sample.md), that uses only the most basic 2 classes
* [Advanced sample](wiki/Advanced_sample.md): style/js includes, add classes dynamically, dynamic table
* [Component sample](wiki/Component_sample.md): automatically include style and/or js
* [Misc samples](wiki/Misc_samples.md)

This sample

* **Run the code:** /sample_normal/view.php
* **HTML code see:** /sample_normal/my_includes and /sample_normal/my_controls

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
```

### Result

![normal_sample.jpg](wiki/img/normal_sample_45.jpg?raw=true "Normal sample")

## Classes overview

<p align="center">
  <img src="https://yuml.me/82a65f5a.png">
</p>

**Basic classes**

* **View:** A view (each view or sub view can have sub views)
* **ListView:** A view composed of a list of views

**Feature classes**

* **SimpleControl:** A simple control that uses no style or JS (input, table, ...)
  * basically a synonym for `View`, uses DSEConfig's controlsFolder as additional folder prefix if set
* **WebPage:** Builds a full web page, ability 2 add style, js and components
  * A component is a piece of html that also needs styles and/or javascript
  * This class is able 2 add these in head and body automatically
* **ComponentBase:** Base class for a component that needs style/js includes and contains implementation
  * or include a component using `$webPage->newComponent()` if you don't need implementation

**Common classes**

* **DSEConfig:** Config for Damn Small Engine
* **ViewBase:** Just a simple base class for all view classes

## LICENSE

Copyright (C) Walter A. Jablonowski 2018-2019, MIT [License](LICENSE)

This library is build upon PHP (license see [credits](credits.md)) and has no further dependecies.\
Licenses of third party software used in samples see [credits](credits.md).


[Privacy](https://walter-a-jablonowski.github.io/privacy.html) | [Legal](https://walter-a-jablonowski.github.io/imprint.html)
