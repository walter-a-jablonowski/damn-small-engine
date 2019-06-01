<?php

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

Represents a view

USAGE:

  see readme

*/
class View /*@*/
{
  protected $scheme;
  protected $values;


  /*@

  ARGS:
    $scheme: file that contains html for this view

  */
  public function __construct( $scheme ) /*@*/
  {
    if( ! file_exists( $scheme ))
      throw new \Exception('DamnSmallEngine: html file missing');
    
    $this->scheme = $scheme;
  }


  /*@ Replace all values */
  
  public function setValues( $values ) /*@*/
  {
    $this->values = $values;
  }

  /*@ Set a value using `$myView->anyName = 'val';` see PHP magic methods */
  
  public function __set( $name, $value ) /*@*/
  {
    $this->values[$name] = $value;
  }


  /*@ Get a value using `$val = $myView->anyName;` see PHP magic methods */
  
  public function __get( $name )
  {
    if( isset( $this->values[$name]))
      return $this->values[$name];

    return "## MISSING VALUE: $name ##";
  }

  /*@

  Get all or a portion of values, defined by a key

  ARGS:
    $key: if given and exists in values only this part of values will be returned, else all values

  */
  protected function getValues( $key = null ) /*@*/
  {
    if( $key && key_exists( $key, $this->values ))
      return $this->values[$key];
    else
      return $this->values;
  }


  /*@ Print view as string */

  public function render() /*@*/
  {
    ob_start();
    include( $this->scheme );
    return ob_get_clean();
  }

  /*@ Use in strings */

  public function __toString() /*@*/
  {
    return $this->render();
  }


  /*@ Escape helper */

  public static function escapeEntities( $s ) /*@*/ // DEV
  {
    return htmlspecialchars( $s );
  }
}

?>
