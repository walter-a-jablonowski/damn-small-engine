<?php

// Copyright (C) Walter A. Jablonowski 2018-2019, MIT License
// https://github.com/walter-a-jablonowski/damn-small-engine

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

Just a simple base class for all view classes

*/
abstract class ViewBase /*@*/
{

  protected $dseController;  // use unusual names, so cant be confused with generic attributes

  protected $dseDirPrefix = '';           // can be part of name like 'ui_'
  protected $dseViewFileEnding = 'html';  // Task: rm this
  protected $dseControlsFolder = '';      // may use 'controls/'
  protected $dseComponentsFolder = '';    // may use 'components/'

  protected $dseUseMin = false;

  protected $dseEscapeFunc;


  /*@

  used internally

  */
  protected function setController( $controller ) /*@*/
  {
    $this->dseController = $controller;
  }


  /*@

  Getter and setter: get form this view or default from DSEConfig

  -------------------------------------------------------@*/

  /*@ */
  public function setDirPrefix( $s )      {  $this->dseDirPrefix = $s;  }
  public function getDirPrefix() /*@*/
  {
    if( $this->dseDirPrefix )
      return $this->dseDirPrefix;
    else
    {
      $config = DSEConfig::instance();
      return $config->getDirPrefix();
    }
  }

  /*@ */
  public function setViewFileEnding( $s ) {  $this->dseViewFileEnding = $s;  }
  public function getViewFileEnding() /*@*/
  {
    if( $this->dseViewFileEnding )
      return $this->dseViewFileEnding;
    else
    {
      $config = DSEConfig::instance();
      return $config->getEscapeFunc();
    }
  }


  /*@ */
  public function setControlsFolder( $s ) {  $this->dseControlsFolder = $s;  }
  public function getControlsFolder() /*@*/
  {
    if( $this->dseControlsFolder )
      return $this->dseControlsFolder;
    else
    {
      $config = DSEConfig::instance();
      return $config->getControlsFolder();
    }
  }

  /*@ */
  public function setComponentsFolder( $s ) {  $this->dseComponentsFolder = $s;  }
  public function getComponentsFolder() /*@*/
  {
    if( $this->dseComponentsFolder )
      return $this->dseComponentsFolder;
    else
    {
      $config = DSEConfig::instance();
      return $config->getComponentsFolder();
    }
  }

  /*@ */
  public function preferMinified( $b ) {  $this->dseUseMin = $b;  }
  public function getPreferMinified() /*@*/
  {
    if( $this->dseUseMin )
      return $this->dseUseMin;
    else
    {
      $config = DSEConfig::instance();
      return $config->getPreferMinified();
    }
  }

  /*@ */
  public function setEscapeFunc( $f ) {  $this->dseEscapeFunc = $f;  }
  public function getEscapeFunc() /*@*/
  {
    if( $this->dseEscapeFunc )
      return $this->dseEscapeFunc;
    else
    {
      $config = DSEConfig::instance();
      return $config->getEscapeFunc();
    }
  }


  /*@ */
  abstract public function render();
  abstract public function __toString(); /*@*/


  /*@
  
  Tools for use in code
  -------------------------------------------------------@*/
  
  /*@
  
  Escape helper

  - Can only access default escape func from DSEConfig, cause static
  
  */
  public static function escape( $s ) /*@*/
  {
    $config = DSEConfig::instance();

    return $config->getEscapeFunc( $s );
  }


  /*@
  
  Tools for use in html
  -------------------------------------------------------@*/

  /*@
  
  Returns " string" or ""

  printString() would just be <?= $this->s ?>
  
  */
  public static function addString( $s ) /*@*/
  {
    if( $s )
      return ' ' . $s;
    else
      return '';
  }
  
  
  /*@
  
  Returns "string " or ""
  
  */
  public static function addLeftString( $s ) /*@*/
  {
    if( $s )
      return $s . ' ';
    else
      return '';
  }


  /*@
  
  See addString()
  
  */
  public static function addClasses( $classNames ) /*@*/
  {
    return self::addString( $classNames );
  }


  /*@
  
  Returns class="string1 string2" or ""
  
  */
  public static function printClasses( $classNames ) /*@*/
  {
    if( $classNames )
      return ' class="' . $classNames . '"';
    else
      return '';
  }


  /*@
  
  Returns $or or a default value
  
  */
  public static function default( $default, $or ) /*@*/
  {
    if( $or )
      return $or;
    else
      return $default;
  }

}

?>
