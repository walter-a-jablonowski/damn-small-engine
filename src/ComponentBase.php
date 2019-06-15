<?php

// Copyright (C) Walter A. Jablonowski 2018-2019, MIT License
// https://github.com/walter-a-jablonowski/damn-small-engine

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

Base class for a component that needs style/js includes and contains implementation

USAGE:

  see readme

*/
abstract class ComponentBase extends View /*@*/
{

  /*@

  ARGS:

    see parent

  */
  public function __construct() /*@*/
  {
    $config = DSEConfig::instance();
    $scheme = $config->getComponentsFolder() . $this->getScheme();

    parent::__construct( $scheme, $this->shouldEscapeAllValues() );
  }

  
  /*@

  */
  public abstract function getScheme();
  public abstract function shouldEscapeAllValues(); /*@*/

}

?>
