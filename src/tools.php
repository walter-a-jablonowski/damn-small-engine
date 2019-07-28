<?php

// Copyright (C) Walter A. Jablonowski 2018-2019, MIT License
// https://github.com/walter-a-jablonowski/damn-small-engine

use WAJ\Lib\Web\DamnSmallEngine;


/*@

Tools for use in code
-------------------------------------------------------@*/
  
/*@

Escape helper

*/
function escape( $s ) /*@*/
{
  $config = DSEConfig::instance();

  $escapeFunc = $config->getEscapeFunc();

  return $escapeFunc( $s );
}

?>
