<?php
/*
 * Converts CSV to JSON
 * Example uses Google Spreadsheet CSV feed
 * csvToArray function found on php.net
 * source: https://gist.github.com/robflaherty/1185299
 */

// Set your CSV feed
$feed = dirname(__FILE__).'/source/year2017-2018.csv';

// Arrays we'll use later
$keys = array();
$CVS_Array = array();

// Function to convert CSV into associative array
function csvToArray($file, $delimiter) {
  if (($handle = fopen($file, 'r')) !== FALSE) {
    $i = 0;
    while (($lineArray = fgetcsv($handle, 4000, $delimiter, '"')) !== FALSE) {
      for ($j = 0; $j < count($lineArray); $j++) {
        $arr[$i][$j] = $lineArray[$j];
      }
      $i++;
    }
    fclose($handle);
  }
  return $arr;
}

// Do it
$data = csvToArray($feed, ',');

// Set number of elements (minus 1 because we shift off the first row)
$count = count($data) - 1;

//Use first row for names
$labels = array_shift($data);

foreach ($labels as $label) {
  $keys[] = $label;
}

// Add Ids, just in case we want them later
$keys[] = 'id';

for ($i = 0; $i < $count; $i++) {
  $data[$i][] = $i;
}

// Bring it all together
for ($j = 0; $j < $count; $j++) {
  $d = array_combine($keys, $data[$j]);
  $CVS_Array[$j] = $d;
}



// Print it out as JSON
echo '<script>';
if(isset($cal)){
	echo '    var semestersInfo ='. json_encode($cal->getSemesters() ).';';
}
echo '   var academics='.json_encode($CVS_Array).';
	</script>';
