<?php

// Copyright (C) Walter A. Jablonowski 2018, MIT License
// https://github.com/walter-a-jablonowski/damn-small-engine

namespace WAJ\Lib\Web\DamnSmallEngine;


/*@

Just a container for view classes. Add view classes, on render() all will be rendered in a list.

USAGE:

  see readme

*/
class ListView extends ViewBase /*@*/
{
  protected $dseList;  // use unusual names, so cant be confused with generic attributes


  /*@ Builds a list class with View instances from given values */
  
  public static function buildList( $scheme, $values, $escapeAllValues = false ) /*@*/
  {
    $s = View::$dirPrefix . "$scheme." . View::$viewFileEnding;

    if( ! file_exists( $s ))
      throw new \Exception( "Damn Small Engine: html file missing $s" );

    $listView = new ListView();

    foreach( $values as $rowValues )
    {
      $entryView = new View( $scheme, $escapeAllValues );
      $entryView->setValues( $rowValues );
      
      $listView->addView( $entryView );
    }

    return $listView;
  }


  /*@
  
  Add an object of View class
  
  */
  public function addView( $view ) /*@*/
  {
    $this->dseList[] = $view;
  }


  /*@
  
  Render all view in $list
  
  */
  public function render( $addBr = true ) /*@*/
  {
    $s = '';
    foreach( $this->dseList as $view )
    {
      if( $s !== '' && $addBr)  $s .= "\n";
      $s .= $view->render();
    }

    return $s;
  }

  /*@
  
  Use in strings
  
  */
  public function __toString() /*@*/
  {
    return $this->render();
  }
}

?>
