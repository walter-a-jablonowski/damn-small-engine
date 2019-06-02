# Damn Small Engine

**PHP low code templating system - simple but powerful**

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

This was tested using PHP 7.1.9, it should work from 5.3 and above.

A simple PHP templating system, based on an idea that I saw somewhere on the internet about 2 years ago. Basically, this uses PHP's output buffering and magic methods. It is a truly awesome concept, because the code is so tiny compared 2 popular templating systems. Use less code, achive more! You can easily read that small code and modify it for your needs. I improved the basic idea and added some features. Have a look at the very small class files in /src.

If you like run the sample code in /sample, which is the same as below.


## Features

* Just 2 small classes, low code
* Build nested html views with data using `$view->subView = $subView;`
* You can also include lists of views using
  * either ListView class (prefered)
  * or as shown in sample "list alternative version" below
* You can build a tree of nested html views (sub view can have sub views ...)
* You can include a list in a list and build list hierarchies
* All nested views are processed at once


## Sample

```php
// Make a view, add values

$view = new View( 'main.html' );

$view->myVal = 'sample value';    // Just add what you need used PHP's magic method __set(), see there
$view->myVal2 = ...               // Please set all values, use at least ''. If one is missing the class will
                                  // print ## MISSING VALUE ##, so you will see in UI that something is missing.

// Add a list

$listData = [                     // Demo data or load from db
  'row 1' => [
    'field 1' => 'entry 1.1',
    'field 2' => 'entry 1.2'
  ],
  'row 2' => [
    'field 1' => 'entry 2.1',
    'field 2' => 'entry 2.2'
  ]
];

$listView = new ListView();         // Instead you may also use:
                                    // $listView = ListView::buildList( 'list1_entry.html', $listData );
foreach( $listData as $rowValues )  // which is just the same code packed in a static method
{
  $entryView = new View( 'list1_entry.html' );
  $entryView->setValues( $rowValues );
  
  $listView->addView( $entryView );
}

$view->list1 = $listView;


// Add a list alternative version

$list2 = new View( 'list2_entries.html' );
$list2->setValues( $listData );
$view->list2 = $list2;


// You could also add: views - in a list - in a view - in a list

$outerList = new ListView();

for( $i=0; $i < 2; $i++ )
{
  $entryView = new View( 'table.html' );
  $innerList = ListView::buildList( 'list1_entry.html', $listData );
  $entryView->list = $innerList;
  
  $outerList->addView( $entryView );
}

$view->listInList = $outerList;


// Build all

echo $view;
```

### main.html

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

    <?= $this->list2 ?>


    <!-- List in list -->
    
    <h3>List in list</h3>

    <table>

      <?= $this->listInList ?>
  
    </table>
    
    
  </body>
</html>
```

### list1_entry.html

```php
<tr><td><?= $this->values['field 1'] ?></td><td><?= $this->values['field 2'] ?></td></tr>
```

### list2_entries.html

```php
<table>

<?php foreach( $this->getValues() as $rowValues ): ?>

  <tr><td><?= $rowValues['field 1'] ?></td><td><?= $rowValues['field 2'] ?></td></tr>

<?php endforeach; ?>

</table>
```

### table.html

```php
<table>

  <?= $this->list ?>

</table>
```


## LICENSE

Copyright (C) Walter A. Jablonowski 2018, MIT [License](LICENSE)
