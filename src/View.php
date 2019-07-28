<?php

// Copyright (C) Walter A. Jablonowski 2018, MIT License
// https://github.com/walter-a-jablonowski/damn-small-engine

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

A view (each view or sub view can have sub views)

USAGE:

  see readme

*/
class View extends ViewBase /*@*/
{
  const EXT_DIFFERS       = 0x1;
  const ESCAPE_ALL_VALUES = 0x2;
  const STRING_MODE       = 0x3;


  protected $dseScheme;              // use unusual names, so cant be confused with generic attributes
  protected $dseExtDifferes;
  protected $dseValues;
  protected $dseEscapeAllValues;


  /*@

  ARGS:

    $scheme: file that contains html for this view
    $escapeAllValues: if true all values should be html escaped on add

  */
  public function __construct( $scheme, $options = 0x0) /*@*/
  {
    $extDiffers      = $options & self::EXT_DIFFERS  ?  true  :  false;
    $escapeAllValues = $options & self::ESCAPE_ALL_VALUES  ?  true  :  false;

    $config = DSEConfig::instance();

    // if( strpos( $scheme, '.js') !== false )

    $this->dseExtDiffers = $extDiffers;

    // Usage of ending is in render, this is just for err handling
    $s = $config->getDirPrefix() . $scheme;
    if( ! $extDiffers )  $s .= '.' . $config->getViewFileEnding();

    if( ! file_exists( $s ))
      throw new \Exception( "Damn Small Engine: html file missing $s" );
  
    $this->dseScheme = $scheme;
    $this->dseEscapeAllValues = $escapeAllValues;
  }


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
  
  Alternative for magic method
  
  */
  public function addSubView( $name, $value ) /*@*/
  {
    $this->$name = $value;  // this just forwards call 2 __get()
  }


  /*@
  
  Get a value using `$val = $myView->anyName;` see PHP magic methods
    
  */
  public function __get( $name )
  {
    if( isset( $this->dseValues[$name]))
      return $this->dseValues[$name];

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
    // TASK: maybe add a func that ensures all values are set, at least '' as option

    $config = DSEConfig::instance();

    // $s = $config->getDirPrefix() . $this->dseScheme . '.' . $config->getViewFileEnding();
    $s = $config->getDirPrefix() . $this->dseScheme;
    if( ! $this->dseExtDiffers )  $s .= '.' . $config->getViewFileEnding();

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

}

?>
