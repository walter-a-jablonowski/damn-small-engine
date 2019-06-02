<?php

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


  /*@ Escape helper */

  public static function escapeEntities( $s ) /*@*/ // DEV
  {
    return htmlspecialchars( $s );
  }
}

?>
