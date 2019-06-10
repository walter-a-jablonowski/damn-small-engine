<?php

// Copyright (C) Walter A. Jablonowski 2019, MIT License
// https://github.com/walter-a-jablonowski/damn-small-engine

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

  A control which is an input, table, ...

  A control doesn't use style files or javascript, if needed use
  addComponent() in Page class
  
  You may use addView() for sub parts (table cell)

USAGE:

  see readme

TASKS:

*/
class Control extends View /*@*/
{

  protected static $controlsFolder = '';  // or use 'controls/'


  /*@

  ARGS:

    see parent

  */
  public function __construct( $scheme, $escapeAllValues = false ) /*@*/
  {
    // $s = self::$dirPrefix . self::$controlsFolder . $this->scheme . '.' . self::$viewFileEnding;


    // $s = self::$dirPrefix . self::$controlsFolder . $this->scheme;
    
    // if( is_dir( $s ))  // TASK
    //   $s .= '/view.' . self::$viewFileEnding;
    // else
    //   $s .= '.' . self::$viewFileEnding;

    parent::__construct( $scheme, $escapeAllValues );
  }


  /*@  */
  public static function setControlsFolder( $s ) {  self::$controls = $s;  } /*@*/


  /*@
  
  Render view

  TASKS:

    - improve, is doubl impl
  
  */
  public function render() /*@*/
  {
    // TASK: maybe add a func that ensures all values are set, at least ''

    $s = self::$dirPrefix . self::$controlsFolder . $this->dseScheme . '.' . self::$viewFileEnding;

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
