<?php

// Copyright (C) Walter A. Jablonowski 2018-2019, MIT License
// https://github.com/walter-a-jablonowski/damn-small-engine

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

Config for Damn Small Engine

*/
class DSEConfig /*@*/
{

  protected $dirPrefix = '';           // can be part of name like 'ui_'
  protected $viewFileEnding = 'html';
  protected $controlsFolder = '';      // may use 'controls/'
  protected $componentsFolder = '';    // may use 'components/'

  protected $useMin = false;

  protected $defaultEscapeFunc = false;

  private static $instance;


  public static function instance( ...$args)
  {
    if( is_null( self::$instance ))

      self::$instance = new self( ...$args );
    
    return self::$instance;
  }

/*
  public function __construct( $scheme, $escapeAllValues = false ) /*@* /
  {

    $this->defaultEscapeFunc = function( $value ) {

      return htmlspecialchars( $value );
    };
  }
*/

  /*@

  DESC:
  
    Change

    - a prefix that will be prepend each path
    - the file ending that will be attached each path

    when making a new view using any of the classes in this lib

  */
  public function getDirPrefix()          {  return $this->dirPrefix;  }
  public function setDirPrefix( $s )      {  $this->dirPrefix = $s;  }
  public function getViewFileEnding()     {  return $this->viewFileEnding;  }
  public function setViewFileEnding( $s ) {  $this->viewFileEnding = $s;  } /*@*/


  /*@  */
  public function getControlsFolder()     {  return $this->controlsFolder;  }
  public function setControlsFolder( $s ) {  $this->controlsFolder = $s;  } /*@*/


  /*@ */
  public function getComponentsFolder()     {  return $this->componentsFolder;  }
  public function setComponentsFolder( $s ) {  $this->componentsFolder = $s;  } /*@*/


  /*@ */
  public function getPreferMinified()  {  return $this->useMin;  }
  public function preferMinified( $b ) {  $this->useMin = $b;  } /*@*/


  /*@ */
  public function getEscapeFunc()  {  return $this->defaultEscapeFunc;  }
  public function setEscapeFunc( $f ) {  $this->defaultEscapeFunc = $f;  } /*@*/


  public function _clone()
  {
    throw new \Exception('Illegal');
  }  
}

?>
