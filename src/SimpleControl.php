<?php

// Copyright (C) Walter A. Jablonowski 2018-2019, MIT License
// https://github.com/walter-a-jablonowski/damn-small-engine

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

  A simple control that uses no style or JS (input, table, ...)

  just synonym for View, adds the ability 2 use a sub folder that you dont need 2 write
    
USAGE:

  see readme

*/
class SimpleControl extends View /*@*/
{


  /*@

  ARGS:

    see parent

  */
  public function __construct( $scheme, $escapeAllValues = false ) /*@*/
  {
    $config = DSEConfig::instance();
    $scheme = $config->getControlsFolder() . $scheme;

    parent::__construct( $scheme, $escapeAllValues );
  }
}

?>
