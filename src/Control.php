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
    $s = $this->dirPrefix . self::$controlsFolder . $scheme . '.' . $this->viewFileEnding;
    
    // $s = $this->dirPrefix . self::$controlsFolder . $scheme;
    
    // if( is_dir( $s ))  // TASK
    //   $s .= '/view.' . $this->viewFileEnding;
    // else
    //   $s .= '.' . $this->viewFileEnding;

    parent::__construct( $s, $escapeAllValues );
  }


  /*@  */
  public static function setControlsFolder( $s ) {  self::$controls = $s;  } /*@*/

}

?>
