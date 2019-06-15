# Damn Small Engine

**PHP low code templating system - small but powerful**


## Misc samples

All samples see [Readme](README.md)

### For loop in html instead code

```php
$list = new View( 'includes/list2_entries' );
$list->setValues( $listData );
$view->list = $list;
```

### view.html

```php
<html>
  <head></head>
  <body>

    <!-- List -->
    
    <h3>List</h3>

    <table>
      <?= $this->list ?>
    </table>
    
    
  </body>
</html>
```

### list2_entries.html

```php
<table>

<?php foreach( $this->getValues() as $rowValues ): ?>

  <tr><td><?= $rowValue['col_1'] ?></td><td><?= $rowValue['col_2'] ?></td></tr>

<?php endforeach; ?>

</table>
```

## LICENSE

Copyright (C) Walter A. Jablonowski 2018-2019, MIT [License](LICENSE)


[Privacy](https://walter-a-jablonowski.github.io/privacy.html) | [Legal](https://walter-a-jablonowski.github.io/imprint.html)