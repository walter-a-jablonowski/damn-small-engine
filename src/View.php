<?php

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

Represents a view

USAGE:

  see readme

*/
class View extends ViewBase /*@*/
{
  const ESCAPE_ALL_VALUES = true;

  protected $scheme;
  protected $values;
  protected $escapeAllValues;


  /*@

  ARGS:

    $scheme: file that contains html for this view
    $escapeAllValues: if true all values should be html escaped on add

  */
  public function __construct( $scheme, $escapeAllValues = false ) /*@*/
  {
    if( ! file_exists( $scheme ))
      throw new \Exception('Damn Small Engine: html file missing');
  
    $this->scheme = $scheme;
    $this->escapeAllValues = $escapeAllValues;
  }


  /*@
  
  Replace all values

  TASKS:
    - escapeAllValues recursive
  
  */
  public function setValues( $values ) /*@*/
  {
    if( $this->escapeAllValues )
      foreach( $values as $name => $value )
        $this->values[$name] = ViewBase::escapeEntities($value);
    else
      $this->values = $values;
  }

  /*@ Set a value using `$myView->anyName = 'val';` see PHP magic methods */
  
  public function __set( $name, $value ) /*@*/
  {
    if( $this->escapeAllValues )
      $this->values[$name] = ViewBase::escapeEntities($value);
    else
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


  /*@ Render view as string */

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
