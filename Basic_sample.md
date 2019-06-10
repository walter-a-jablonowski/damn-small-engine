# Damn Small Engine

**PHP low code templating system - small but powerful**

## Basic sample

Minimal sample, also showing some additional features. This only uses the classes **View** and **ListView**. For the advanced samples see [Readme](README.md)

* **Run the code:** /sample_basic/view.php
* **HTML code see below or:** /sample_basic/includes

```php
// Demo data

$listData = [                     // or load from db
  'row 1' => [
    'field 1' => 'entry 1.1',
    'field 2' => 'entry 1.2'
  ],
  'row 2' => [
    'field 1' => 'entry 2.1',
    'field 2' => 'entry 2.2'
  ]
];


// Make a view, add values            // You could add View( ..., View::ESCAPE_ALL_VALUES ) which
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


// Alternative version

$list5 = new View( 'includes/list2_entries' );
$list5->setValues( $listData );
$view->list5 = $list5;


// You could also add: views - in a list - in a view - in a list

$outerList = new ListView();

for( $i=0; $i < 2; $i++ )
{
  $entryView = new View( 'includes/table' );
  $innerList = ListView::buildList( 'includes/list1_entry', $listData );
  $entryView->list = $innerList;
  
  $outerList->addView( $entryView );
}

$view->listInList = $outerList;


// Build all

echo $view;
```

### includes/main.html

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
    
    
    <!-- List 5 -->
    
    <h3>List 5</h3>

    <?= $this->list5 ?>


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

### includes/list1_entry.html

This is how you would use it if there is a blank in value name.

```php
<tr><td><?= $this->dseValues['field 1'] ?></td><td><?= $this->dseValues['field 2'] ?></td></tr>
```

### includes/list2_entries.html

```php
<table>

<?php foreach( $this->getValues() as $rowValues ): ?>

  <tr><td><?= $rowValues['field 1'] ?></td><td><?= $rowValues['field 2'] ?></td></tr>

<?php endforeach; ?>

</table>
```

## LICENSE

Copyright (C) Walter A. Jablonowski 2018-2019, MIT [License](LICENSE)
