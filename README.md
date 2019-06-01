# Damn small engine

[![License: MIT](https://img.shields.io/badge/License-MIT-yellow.svg)](https://opensource.org/licenses/MIT)

**PHP low code templating system**

This was tested using PHP 7.1.9, it should work from 5.3 and above.

A simple PHP templating system, based on an idea that I saw somewhere on the internet about 2 years ago. Basically, this uses PHP's output buffering and magic methods. It is a truly awesome concept, because the code is so tiny compared 2 popular templating systems. Use less code, achive more! You can easily read that small code and modify it for your needs. I improved the basic idea and added some features. Have a look at the very small class files in /src.

If you like run the sample code in /sample, which is the same as below.


## Features

* Views can be nested using `$view->subView = $subView;` (you can build a tree of nested html view)
* You can also include lists of views using
  * either ListView class (prefered)
  * or as shown in sample 2 below
* You can include a list in a list and build hierarchies
* All nested views are procecced at the end when the complete tree has been build


## Sample

```php
// Make a view, add values

$view = new View( 'main.html' );

$view->myVal = 'sample value';    // Just add what you need used PHP's magic method __set(), see there
$view->myVal2 = ...               // Please set all values, use at least ''. If one is missing the class will
                                  // print ## MISSING VALUE ##, so you will see in UI that something is missing.

// Add a list

$listData = [                     // Demo data or load from  db
  'row 1' => [
    'field 1' => 'entry 1.1',
    'field 2' => 'entry 1.2'
  ],
  'row 2' => [
    'field 1' => 'entry 2.1',
    'field 2' => 'entry 2.2'
  ]
];

$listView1 = new ListView();        // Instead you may also use: $listView1 = ListView::buildList( 'list1_entry.html', $listData );
                                    // which is just the same code packed in a static method
foreach( $listData as $rowValues )
{
  $entryView = new View( 'list1_entry.html' );
  $entryView->setValues( $rowValues );
  
  $listView1->addView( $entryView );
}

$view->list1 = $listView1;


// Add a list alternative version

$list2 = new View( 'list2_entries.html' );
$list2->setValues( $listData );
$view->list2 = $list2;


// You could also add a list 2 a list

$outerList = new ListView();
$outerList->addView( $listView1 );
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
    
    
    <h3>List 1</h3>

    <table>

      <?= $this->list1 ?>

    </table>
    
    
    <h3>List 2</h3>

    <?= $this->list2 ?>


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


## Do

* Sample list in list larger
* Escape
* composer stable
* github license basge
* buy m a coffee


## LICENSE

Copyright (C) Walter A. Jablonowski 2018, MIT License see [LICENSE](LICENSE)
