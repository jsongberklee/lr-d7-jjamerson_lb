<?php 
if(!empty($row->field_field_affiliated_dept_ref)) {
  $total_elements = count($row->field_field_affiliated_dept_ref);
  $counter = $total_elements;
  $count = 0;
  while ($count != ($counter)) {
    if($row->field_field_affiliated_dept_ref[$count]['raw']['target_id'] == '2206722') {
      $output = str_replace('department/berklee-online', 'berklee-online', $row->field_field_affiliated_dept_ref[$count]['rendered']['#markup']) . ' (<a class="external-link-icon" href="https://online.berklee.edu/' . str_replace('people','faculty',drupal_lookup_path('alias','node/'.$row->nid)) . '" target="_blank">available courses</a>)';
      print_r($output);
    }
    else {
      print_r($row->field_field_affiliated_dept_ref[$count]['rendered']['#markup']);
    }
    $count = $count + 1;
    echo ($count != $counter)  ? ", " : "";
  }
}