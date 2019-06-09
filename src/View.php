<?php

// Copyright (C) Walter A. Jablonowski 2018, MIT License
// https://github.com/walter-a-jablonowski/damn-small-engine

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

Represents a view

USAGE:

  see readme

*/
class View extends ViewBase /*@*/
{
  const ESCAPE_ALL_VALUES = true;

  protected static $dirPrefix = '';  // can be part of name like ui_
  protected static $viewFileEnding = 'html';

  protected $dsePage;                // use unusual names, so cant be confused with generic attributes

  protected $dseScheme;
  protected $dseValues;
  protected $dseEscapeAllValues;


  /*@

  ARGS:

    $scheme: file that contains html for this view
    $escapeAllValues: if true all values should be html escaped on add

  */
  public function __construct( $scheme, $escapeAllValues = false ) /*@*/
  {
    if( ! file_exists( $scheme ))
      throw new \Exception( "Damn Small Engine: html file missing $scheme" );
  
    $this->dseScheme = $scheme;
    $this->dseEscapeAllValues = $escapeAllValues;
  }


  /*@ */
  public static function setDirPrefix( $s )      {  self::$dirPrefix = $s;  }
  public static function setViewFileEnding( $s ) {  self::$viewFileEnding = $s;  } /*@*/


  /*@

  */
  public function setPage( $page ) /*@*/
  {
    $this->dsePage = $page;
  }


  /*@
  
  Replace all values

  TASKS:
    
    - escapeAllValues recursive
  
  */
  public function setValues( $values ) /*@*/
  {
    if( $this->dseEscapeAllValues )
      foreach( $values as $name => $value )
        $this->dseValues[$name] = ViewBase::escapeEntities($value);
    else
      $this->dseValues = $values;
  }

  /*@
  
  Set a value using `$myView->anyName = 'val';` see PHP magic methods
  
  */
  public function __set( $name, $value ) /*@*/
  {
    if( $this->dseEscapeAllValues )
      $this->dseValues[$name] = ViewBase::escapeEntities($value);
    else
      $this->dseValues[$name] = $value;
  }


  /*@
  
  Get a value using `$val = $myView->anyName;` see PHP magic methods
    
  */
  public function __get( $name )
  {
    if( isset( $this->dseValues[$name]))
      return $this->dseValues[$name];

    // return "## MISSING VALUE: $name ##";
    return '';
  }

  /*@

  Get all or a portion of values, defined by a key

  ARGS:
    $key: if given and exists in values only this part of values will be returned, else all values

  */
  protected function getValues( $key = null ) /*@*/
  {
    if( $key && key_exists( $key, $this->dseValues ))
      return $this->dseValues[$key];
    else
      return $this->dseValues;
  }


  /*@
  
  Render view as string
  
  */
  public function render() /*@*/
  {
    // TASK: maybe add a func that ensures all values are set, at least ''

    ob_start();
    include( $this->dseScheme );
    return ob_get_clean();
  }

  /*@
  
    Use in strings

  */
  public function __toString() /*@*/
  {
    return $this->render();
  }


  /*@
  
  Escape helper
  
  */
  public static function escape( $s ) /*@*/ // DEV
  {
    return htmlspecialchars( $s );
  }
}

?>
