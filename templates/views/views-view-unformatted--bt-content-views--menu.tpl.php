<?php

/**
* @file
* Default simple view template to display a list of rows.
*
* @ingroup views_templates
*/

if (!empty($title)) {
  $classes = '';
  if (count($rows) == 1) {
    $classes .= ' single-group-row ';
    $formatted_link_title = trim(strip_tags( reset($rows) ) );
    if ( $title == $formatted_link_title ) {
      $classes .= ' titles-match ';
    } 
  } 
  echo "<div class='views-row-group {$classes}'>";
  echo "<h3>{$title}</h3>";  
  foreach ($rows as $id => $row) {
    if ($classes_array[$id]) {
      $classes .= $classes_array[$id];
    } 
    echo "<div class='{$classes}'>{$row}</div>";
  }
  echo "</div>";
}
