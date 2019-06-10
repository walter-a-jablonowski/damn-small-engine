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

  public static $dirPrefix = '';  // can be part of name like ui_
  public static $viewFileEnding = 'html';

  protected $dseScheme;              // use unusual names, so cant be confused with generic attributes
  protected $dseValues;
  protected $dseEscapeAllValues;


  /*@

  ARGS:

    $scheme: file that contains html for this view
    $escapeAllValues: if true all values should be html escaped on add

  */
  public function __construct( $scheme, $escapeAllValues = false ) /*@*/
  {
    $s = self::$dirPrefix . "$scheme." . self::$viewFileEnding;

    if( ! file_exists( $s ))
      throw new \Exception( "Damn Small Engine: html file missing $s" );
  
    $this->dseScheme = $scheme;
    $this->dseEscapeAllValues = $escapeAllValues;
  }


  /*@

  DESC:
  
    Change

    - a prefix that will be prepend each path
    - the file ending that will be attached each path

    when making a new view using any of the classes in this lib

  */
  public static function setDirPrefix( $s )      {  self::$dirPrefix = $s;  }
  public static function setViewFileEnding( $s ) {  self::$viewFileEnding = $s;  } /*@*/


  /*@
  
  Replace all values

  TASKS:
    
    - escapeAllValues recursive
  
  */
  public function setValues( $values ) /*@*/
  {
    $this->dseValues = [];
  
    if( $this->dseEscapeAllValues )
      foreach( $values as $name => $value )
        $this->dseValues[$name] = ViewBase::escape($value);
    else
      $this->dseValues = $values;
  }

  /*@
  
  Set a value using `$myView->anyName = 'val';` see PHP magic methods
  
  */
  public function __set( $name, $value ) /*@*/
  {
    if( $this->dseEscapeAllValues )
      $this->dseValues[$name] = ViewBase::escape($value);
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

  Get all or a portion of values, defined by a key, use in html

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
  
  Render view
  
  */
  public function render() /*@*/
  {
    // TASK: maybe add a func that ensures all values are set, at least ''

    $s = self::$dirPrefix . $this->dseScheme . '.' . self::$viewFileEnding;

    ob_start();
    include( $s );
    return ob_get_clean();
  }

  /*@
  
  For use in strings

  */
  public function __toString() /*@*/
  {
    return $this->render();
  }


  /*@
  
  Just tools for outside use
  -------------------------------------------------------@*/
  
  /*@
  
  Escape helper
  
  */
  public static function escape( $s ) /*@*/
  {
    return htmlspecialchars( $s );
  }


  /*@
  
  Returns " class1 class2" or "" if no class
  
  */
  public static function addClasses( $classNames ) /*@*/
  {
    if( $classNames )
      return ' ' . $classNames;
    else
      return '';
  }


  /*@
  
  Returns class="class1 class2" or "" if no class
  
  */
  public static function printClass( $classNames ) /*@*/
  {
    if( $classNames )
      return ' class="' . $classNames . '"';
    else
      return '';
  }
}

?>
