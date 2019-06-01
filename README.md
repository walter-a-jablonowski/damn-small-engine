# Damn small engine

**PHP low code templating system**

A simple PHP templating system, based on an idea that I saw somewhere on the internet about 2 years ago. Basically, this uses PHP's output buffering and magic methods. It is a truly awesome concept, because the code is so tiny compared 2 popular templating systems. Use less code, achive more! You can easily read the code and modify it for your needs. I added some features 2 the basic idea. Have a look at the 2 very small class files in /src.

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

$view->myVal = 'sample value';    // just add what you need used PHP's magic method __set(), see there
$view->myVal2 = ...               // Please set all values at least '', if one is missing the class will
                                  // print ## MISSING VALUE ##, so you will see in UI and can't forget anything

// Add a list

$listData = [
  'row 1' => [
    'field 1' => 'entry 1.1',
    'field 2' => 'entry 1.2'
  ],
  'row 2' => [
    'field 1' => 'entry 2.1',
    'field 2' => 'entry 2.2'
  ]
];

$listView1 = new ListView();

foreach( $listData as $rowValues )
{
  $entryView = new View( 'list1_entry.html' );
  $entryView->setValues( $rowValues );
  
  $listView1->addView( $entryView );
}

// instead you may also use $listView1 = ListView::buildList( 'list1_entry.html', $listData );
// which is just the same code packed in a static method

$view->list1 = $listView1;


// or

$list2 = new View( 'list2_entries.html' );
$list2->setValues( $listData );
$view->list2 = $list2;


// you could also add a list 2 a list

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


## LICENSE

Copyright (C) Walter A. Jablonowski 2018, MIT License
