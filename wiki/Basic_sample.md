# Damn Small Engine

**PHP low code templating system - small but powerful**


## Basic sample

Minimal sample, also showing some additional features. This only uses the classes **View** and **ListView**. For the advanced samples see [Readme](README.md). See also class comments in /src (API desc currently missing).

* **Run the code:** /sample_basic/view.php
* **HTML code see below or:** /sample_basic/includes

```php
// Some config

$config = DSEConfig::instance();

if( $env == DEBUG )     $config->preferMinified( false );  // should use minified version ?
elseif( $env == PROD )  $config->preferMinified( true );

$config->setDirPrefix( 'my_' );  // a folder prefix that you can leave out on new View( ... )
// $config->setControlsFolder('controls/');


// Demo data

$listData = ...


// Make a view, add values            // You could add View( ..., DSEConfig::ESCAPE_ALL_VALUES ) which
                                      //   is htmlspecialchars() for all added values, or do it yourself
$view = new View( 'includes/main' );  // The '.html' will be added => 'includes/main.html'
$view->myVal = 'sample value';        // Just add what you need
// $view->myVal2 = ...
// $view->myVal2 = View::escape( $myStr );  // Escape helper
                                  

// Add a list

// Version 1

$listView = ListView::buildList( 'includes/list1_entry', $listData );
$view->list1 = $listView;


// Version 2

$listView = new ListView();         // The sample above is just this code packed in a static method
                                    // you might want use this version if you need 2 make something special
// /*
foreach( $listData as $rowValues )
{
  $entryView = new View( 'includes/list1_entry' );
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
```

### includes/view.html

```php
<html>
  <head></head>
  <body>

    I am a <?= $this->myVal ?>.


    <!-- List 1 -->

    <h3>List 1</h3>

    <table>
      <?= $this->list1 ?>
    </table>

    
    <!-- List 2 -->
    
    <h3>List 2</h3>

    <table>
      <?= $this->list2 ?>
    </table>
    
    
    <!-- List in list -->
    
    <h3>List in list</h3>

    <table>
      <?= $this->listInList ?>
    </table>
    
    
  </body>
</html>
```

### includes/table.html

```php
<table>

  <?= $this->list ?>

</table>
```

### includes/list_entry.html

This is how you would use it if there is a blank in value name.

```php
<tr><td><?= $this->dseValues['field 1'] ?></td><td><?= $this->dseValues['field 2'] ?></td></tr>
```

## LICENSE

Copyright (C) Walter A. Jablonowski 2018-2019, MIT [License](LICENSE)


[Privacy](https://walter-a-jablonowski.github.io/privacy.html) | [Legal](https://walter-a-jablonowski.github.io/imprint.html)