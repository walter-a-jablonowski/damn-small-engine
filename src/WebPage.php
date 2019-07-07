<?php

// Copyright (C) Walter A. Jablonowski 2018-2019, MIT License
// https://github.com/walter-a-jablonowski/damn-small-engine

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

  Builds a full web page, ability 2 add style, js and components
  
  A component is a piece of html that also needs styles and/or
  javascript. This class is able 2 add these in head and body automatically.

  You beed 2 add following anchors in the html file for this class:

  - $this->styleIncludes
  - $this->pageStyle
  - $this->JSIncludes
  - $this->pageJS
  
  - $this->content

USAGE:

  see readme

TASKS:

  - seems no need: ensure all views have a parent (maybe additional member $parent) on render,
    cause while dev you would see it in UI which is enough (should ne simple)

*/
class WebPage extends View /*@*/
{

  protected $dseAllView;  // Just for fun, seems no need (see class comment), but the code seems nicer

  protected $dseStyleInc = [];
  protected $dsePageStyle = [];
  protected $dseJSInc = [];
  protected $dsePageJS = [];


  /*@

  ARGS:

    $scheme: file that contains html for this view
    $escapeAllValues: if true all values should be html escaped on add

  */
  public function __construct( $scheme ) /*@*/
  {
    parent::__construct( $scheme, false );

    $this->styleIncludes = [];  // this is in html
    $this->pageStyle     = [];
    $this->JSIncludes    = [];
    $this->pageJS        = [];

    $this->content       = '';
  }


  /*@ */
  public function addStyleInclude( $styleInc ) /*@*/
  {
    $config = DSEConfig::instance();

    $s = str_replace( '.css', '.min.css', $styleInc); // TASK: improve
    if( $config->getPreferMinified() && file_exists( $s))
      $styleInc = $s;

    $this->dseStyleInc[] = '<link href="' . $config->getDirPrefix() . $styleInc . '" rel="stylesheet">';
  }


  /*@ */
  public function addStyle( $style ) /*@*/
  {
    $this->dsePageStyle[] = $style;
  }


  /*@ */
  public function addJSInclude( $jsInc ) /*@*/
  {
    $config = DSEConfig::instance();

    $s = str_replace( '.js', '.min.js', $jsInc); // TASK: improve
    if( $config->getPreferMinified() && file_exists( $s))
      $jsInc = $s;

    $this->dseJSInc[] = '<script src="' . $config->getDirPrefix() . $jsInc . '"></script>';
  }


  /*@ */
  public function addJS( $js ) /*@*/
  {
    $this->dsePageJS[] = $js;
  }


  // Just for fun, seems no need (see class comment), but the code seems nicer

  /*@ */
  public function newView( $scheme, $escapeAllValues = false ) /*@*/
  {
    $view = new View( $scheme, $escapeAllValues );
    $view->setController($this);
    $this->dseAllView[] = $view;

    return $view;
  }

  /*@
  
  Synonym for addView(), use for large block of HTML
    
  */
  public function newBlock( $scheme, $escapeAllValues = false ) /*@*/
  {
    return $this->newView( $scheme, $escapeAllValues );
  }

  /*@
  
  Synonym for addView(), use for small HTML

  TASK
  
  */
  public function newBrick( $scheme, $escapeAllValues = false ) /*@*/
  {
    return $this->newView( $scheme, $escapeAllValues );
  }


  /*@ */
  public function newListView() /*@*/
  {
    $list = new ListView();
    $list->setController($this);
    $this->dseAllView[] = $list;

    return $list;
  }


  /*@ */
  public function newSimpleControl( $scheme, $escapeAllValues = false ) /*@*/
  {
    $view = new SimpleControl( $scheme, $escapeAllValues );
    $view->setController($this);
    $this->dseAllView[] = $view;

    return $view;
  }


  /*@

  Add a component which is html that has own style and/or js
  
  */
  public function newComponent( $scheme, $escapeAllValues = false ) /*@*/
  {
    $config = DSEConfig::instance();
    
    $viewFile = $config->getComponentsFolder() . "$scheme/view";
    $this->loadDependencies( $scheme, $escapeAllValues );

    $view = $this->addView( $viewFile, $escapeAllValues );

    return $view;
  }


  /*@

  Add a component that also has implementation
  
  */
  public function newDerivedComponent( $class, ...$args ) /*@*/
  {
    
    $c = new $class( ...$args );
    
    $this->loadDependencies( $c->getScheme() );

    return $c;
  }


  /*@

  Add a class derived from a DSE class which is no component
  
  */
  public function newSpecView( $class, ...$args ) /*@*/
  {
    
    $c = new $class( ...$args );
    
    return $c;
  }


  // Load component dependencies in local arrays (normal comment cause private)

  private function loadDependencies( $scheme, $escapeAllValues = false )
  {
    $config = DSEConfig::instance();

    $styleIncSrc = $config->getDirPrefix() . $config->getComponentsFolder() . "$scheme/style_includes";
    $styleSrc    = $config->getDirPrefix() . $config->getComponentsFolder() . "$scheme/style.css";
    $jsIncSrc    = $config->getDirPrefix() . $config->getComponentsFolder() . "$scheme/js_includes";
    $jsSrc       = $config->getDirPrefix() . $config->getComponentsFolder() . "$scheme/code.js";

    
    // Inc styles
    
    if( is_dir( $styleIncSrc ))  // DEV
    {
      $f = scandir( $styleIncSrc );
      
      foreach( $f as $name)
      {
        $a = pathinfo( $name );  $e = $a['extension'];
        
        // if( ! in_array( $name, ['.', '..']) && strpos( $name, '.min') === false) // && is_readable($full))
        if( $e == 'js' && strpos( $name, '.min') === false) // && is_readable($full))
          $this->addStyleInclude( $styleIncSrc . '/' . $name );  // TASK: improve
      }

    }
    
    // Page Style
    
    if( file_exists( $styleSrc ))
      $this->addStyleInclude( file_get_contents( $styleSrc ));

    // Inc JS

    if( is_dir( $jsIncSrc ))  // DEV
    {
      $f = scandir( $jsIncSrc );

      foreach( $f as $name)
      {
        $a = pathinfo( $name );  $e = $a['extension'];
        
        // if( ! in_array( $name, ['.', '..']) && strpos( $name, '.min') === false) // && is_readable($full))
        if( $e == 'css' && strpos( $name, '.min') === false) // && is_readable($full))
          $this->addStyleInclude( $jsIncSrc . '/' . $name );  // TASK: improve
      }
    }

    // Page JS
    
    if( file_exists( $jsSrc ))
      $this->addJSInclude( file_get_contents( $jsSrc ));
  }


  /*@ */
  public function attachContent( $content ) /*@*/
  {
    $this->content = $content;
  }


  /*@ */
  public function render() /*@*/
  {
    // TASK: ensure needed anchoes are there for style and js

    // All styles and js from local arrays => anchors
    
    $this->styleIncludes = array_unique( $this->dseStyleInc );
    $this->pageStyle     = array_unique( $this->dsePageStyle );
    $this->JSIncludes    = array_unique( $this->dseJSInc );
    $this->pageJS        = array_unique( $this->dsePageJS );

    $this->styleIncludes = implode( "\n", $this->styleIncludes);
    $this->pageStyle     = implode( "\n", $this->pageStyle);
    $this->JSIncludes    = implode( "\n", $this->JSIncludes);
    $this->pageJS        = implode( "\n", $this->pageJS);
    
    $this->pageStyle     = "<style>\n\n"  . $this->pageStyle . "\n\n</style>";
    $this->pageJS        = "<script>\n\n" . $this->pageJS    . "\n\n</script>";


    return parent::render();
  }

  /*@
    
  Use in strings
  
  */
  public function __toString() /*@*/
  {
    return $this->render();
  }
}

?>
