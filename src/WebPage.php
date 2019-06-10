<?php

// Copyright (C) Walter A. Jablonowski 2019, MIT License
// https://github.com/walter-a-jablonowski/damn-small-engine

namespace WAJ\Lib\Web\DamnSmallEngine;

require 'kint.phar';


/*@

  A full web page, ability 2 add style, js and components
  
  A component is a piece of html that also needs styles and/or
  javascript. This class is able 2 add these in head and body automatically.

  You beed 2 add following anchors in the html file for this class:

  - $this->styleIncludes
  - $this->pageStyle
  - $this->JSIncludes
  - $this->pageJS
  
  - $this->content

  Also holds common vars (overwrites ViewBase defaults)

USAGE:

  see readme

TASKS:

  - seems no need: ensure all views have a parent (maybe additional member $parent) on render,
    cause while dev you would see it in UI which is enough (should ne simple)

*/
class WebPage extends View /*@*/
{

  protected static $componentsFolder = ''; // or use components/
  protected static $dseUseMin = false;
  
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
  public static function setComponentsFolder( $s ) {  self::$componentsFolder = $s;  } /*@*/


  /*@ */
  public static function preferMinified( $dseUseMin ) /*@*/
  {
    self::$dseUseMin = $dseUseMin;
  }



  /*@ */
  public function addStyleInclude( $styleInc ) /*@*/
  {
    $s = str_replace( '.css', '.min.css', $styleInc); // TASK: improve
    if( self::$dseUseMin && file_exists( $s))
      $styleInc = $s;

    $styleInc = self::$dirPrefix . $this->scheme;

    $this->dseStyleInc[] = '<link href="' . $styleInc . '" rel="stylesheet">';
  }


  /*@ */
  public function addStyle( $style ) /*@*/
  {
    $this->dsePageStyle[] = $style;
  }


  /*@ */
  public function addJSInclude( $jsInc ) /*@*/
  {
    $s = str_replace( '.js', '.min.js', $jsInc); // TASK: improve
    if( self::$dseUseMin && file_exists( $s))
      $jsInc = $s;

    $jsInc = self::$dirPrefix . $this->scheme;

    $this->dseStyleInc[] = '<script src="' . $jsInc . '"></script>';
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

  /*@ */
  public function newControl( $scheme, $escapeAllValues = false ) /*@*/
  {
    $view = new Control( $scheme, $escapeAllValues );
    $view->setController($this);
    $this->dseAllView[] = $view;

    return $view;
  }

  /*@ */
  public function newListView() /*@*/
  {
    $list = new ListView();
    $list->setController($this);
    $this->dseAllView[] = $list;

    return $list;
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


  /*@

  Add a component which is html that has own style and/or js
  
  */
  public function newComponent( $scheme, $escapeAllValues = false ) /*@*/
  {

    $styleIncSrc = self::$componentsFolder . "$scheme/style_includes";
    $styleSrc    = self::$componentsFolder . "$scheme/style.css";
    $viewFile    = self::$componentsFolder . "$scheme/view";
    $jsIncSrc    = self::$componentsFolder . "$scheme/js_includes";
    $jsSrc       = self::$componentsFolder . "$scheme/code.js";

    
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


    // View  
    
    $view = $this->addView( $viewFile, $escapeAllValues );
    
    return $view;
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

    $this->dseStyleInc  = array_unique( $this->dseStyleInc );
    $this->dsePageStyle = array_unique( $this->dsePageStyle );
    $this->dseJSInc     = array_unique( $this->dseJSInc );
    $this->dsePageJS    = array_unique( $this->dsePageJS );

    $this->styleIncludes = implode( "\n", $this->dseStyleInc);
    $this->pageStyle     = implode( "\n", $this->dsePageStyle);
    $this->JSIncludes    = implode( "\n", $this->dseJSInc);
    $this->pageJS        = implode( "\n", $this->dsePageJS);
    
    $this->pageStyle     = "<style>\n\n"  . implode( "\n", $this->dsePageStyle) . "\n\n</style>";
    $this->pageJS        = "<script>\n\n" . implode( "\n", $this->dsePageJS)    . "\n\n</script>";

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
