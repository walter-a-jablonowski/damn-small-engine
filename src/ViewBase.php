<?php

// Copyright (C) Walter A. Jablonowski 2019, MIT License
// https://github.com/walter-a-jablonowski/damn-small-engine

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

Base class for a view

*/
abstract class ViewBase /*@*/
{

  /*@ */
  abstract public function render(); /*@*/

  /*@ */
  abstract public function __toString(); /*@*/

}

?>
