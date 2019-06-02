<?php

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

Just a container for view classes. Add view classes, on render() all will be rendered in a list.

USAGE:

  see readme

*/
class ListView extends ViewBase /*@*/
{
  protected $list;


  public function __construct()
  {
  }


  /*@ Builder */
  
  public static function buildList( $scheme, $values ) /*@*/
  {
    if( ! file_exists( $scheme ))
      throw new \Exception('DamnSmallEngine: html file missing');

    $listView = new ListView();

    foreach( $values as $rowValues )
    {
      $entryView = new View( $scheme );
      $entryView->setValues( $rowValues );
      
      $listView->addView( $entryView );
    }

    return $listView;
  }


  /*@ Add an object of View class */

  public function addView( $view ) /*@*/
  {
    $this->list[] = $view;
  }


  /*@ Render all view in $list */

  public function render( $addBr = true ) /*@*/
  {
    $s = '';
    foreach( $this->list as $view )
    {
      if( $s !== '' && $addBr)  $s .= "\n";
      $s .= $view->render();
    }

    return $s;
  }

  /*@ Use in strings */

  public function __toString() /*@*/
  {
    return $this->render();
  }
}

?>
