# Damn Small Engine

**PHP low code templating system - small but powerful**


## Component samples

**Building a component or derived component**

**still debugging this one**

A component is a piece of html that depends on certain style and/or js includes or code. It is the more complex version of a SimpleControl. The There a 2 method buiding a component. WebPage->newComponent() or derive the ComponentBase class. In both cases style/js will automatically included. Just add yout html block, and Damn Small Engine will care about neccessary includes. Use ComponentBase if you als need 2 have special PHP implementation for your component.

### Using WebPage->newComponent()

```php
// See Normal Sample in readme for inti code

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
```

### Derive ComponentBase

```php
// MyComponent.php
// Derive and provide Implementation

use WAJ\Lib\Web\DamnSmallEngine\ComponentBase;
// ...


class MyComponent extends ComponentBase /*@*/
{

  public function __construct()
  {

    // Provide your component specific implementation

    // Access values
    // $this->dseValues;

    parent::__construct();
  }

  
  /*

  You need 2 provide the name of the html/style/js folder

  */
  public function getScheme()
  {
    return 'components/demo_comp';
  }


  /*

  */
  public function shouldEscapeAllValues(); /*@*/
  {
    return false;
  }

}

// Usage
```

## LICENSE

Copyright (C) Walter A. Jablonowski 2018-2019, MIT [License](LICENSE)


[Privacy](https://walter-a-jablonowski.github.io/privacy.html) | [Legal](https://walter-a-jablonowski.github.io/imprint.html)