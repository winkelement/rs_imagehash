<?php
include '../../../include/db.php';
include '../../../include/authenticate.php';
include '../../../include/general.php';
require '../include/init.php';

$start_1 = microtime(true);
$ID = filter_input(INPUT_GET, 'ref', FILTER_VALIDATE_INT);
if (!$ID) {
    exit ('NO REFERENCE');
}
$hash_ref = sql_value("SELECT imagehash value from resource WHERE ref = '$ID'", '');
$hashes = sql_query("SELECT ref, imagehash from resource WHERE ref > '0' LIMIT 60");

//var_dump($ID);
//var_dump($hash_ref);
//var_dump($hashes);
//exit();

$hasher = new ImageHash;
        foreach ($hashes as $key_1 => $value_1) {
                $distance = $hasher->distance($hash_ref, $value_1['imagehash']);
                if ($distance < 20) {
                    $hash_results[$n] = ['ref' => $value_1['ref'], 'distance' => $distance];
                    $n++;
                }
        }
        //var_dump($hash_results);
//exit();



//$distance = $hasher->distance($hash_2, $hash_1);
//$time_dist = microtime(true) - $start_dist;

//var_dump($distance);
$time_1 = microtime(true) - $start_1;
echo ($time_1) . " time for " . count($hashes);
//echo round($time_2, 4) . " hash 2<br>";
//echo round($time_dist, 4) . " distance<br>";
//echo round(($time_1 + $time_2 + $time_dist), 4) . " total<br>";