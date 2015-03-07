<?php
include '../../../include/db.php';
include '../../../include/authenticate.php';
include '../../../include/general.php';
require '../include/init.php';

$ID = filter_input(INPUT_GET, 'ref', FILTER_VALIDATE_INT);
if (!$ID) {
    exit ('NO REFERENCE');
}
$hash_ref = sql_value("SELECT imagehash value from resource WHERE ref = '$ID'", '');

$start_1 = microtime(true);
$hashes_1 = sql_query("SELECT ref, imagehash from resource WHERE ref > '0'");
$hasher = new ImageHash;
foreach ($hashes_1 as $key_1 => $value_1) {
        $distance = $hasher->distance($hash_ref, $value_1['imagehash']);
        if ($distance < 20) {
            $hash_results[$n] = ['ref' => $value_1['ref'], 'distance' => $distance];
            $distances_1[$n] = $distance;
            $n++;
        }
}
if (isset($distances_1) && count($distances_1) > 1) {
    array_multisort($distances_1, SORT_ASC, $hash_results);
}

$time_1 = microtime(true) - $start_1;

$start_2 = microtime(true);
$i = 0;
$hashes_2 = sql_query("SELECT ref, BIT_COUNT(imagehash ^ '$hash_ref') as distance from resource "
        . "WHERE ref > '0' "
        . "AND imagehash IS NOT NULL "
        . "AND resource_type = '1' "
        . "HAVING distance < 20 "
        . "ORDER BY distance");
//foreach ($hashes_2 as $value) {
//    if ($value['distance'] < 20) {
//        $hash_results_2[$i] = ['ref' => $value['ref'], 'distance' => $value['distance']];
//        $i++;
//    } 
//}
$time_2 = microtime(true) - $start_2;


echo ($time_1) . " time 1 for " . count($hashes_1) . "<br>";
echo ($time_2) . " time 2 for " . count($hashes_2) . " time per image: " .($time_2/count($hashes_1)) . "<br>";
echo (100 - (($time_2 * 100) / $time_1));
var_dump($hash_results);
var_dump($hashes_2);
//echo round($time_2, 4) . " hash 2<br>";
//echo round($time_dist, 4) . " distance<br>";
//echo round(($time_1 + $time_2 + $time_dist), 4) . " total<br>";