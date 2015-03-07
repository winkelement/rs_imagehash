<?php
include '../../../include/db.php';
include '../../../include/authenticate.php';
include '../../../include/general.php';
require '../include/init.php';

$start_1 = microtime(true);

$recreate = filter_input(INPUT_GET, 'recreate', FILTER_VALIDATE_BOOLEAN);

if ($recreate) {
    $recreate_condition = '';
} else {
    $recreate_condition = 'AND imagehash IS NULL';
}

$resources_newhash = sql_array("SELECT ref value from resource "
        . "WHERE resource_type = '1' "
        . "AND ref > 0 "
        . "AND preview_extension = 'jpg' "
        . "'$recreate_condition'"
        . "ORDER BY creation_date DESC LIMIT 1000");

$count = count($resources_newhash);

if (!$count) {
    exit ('ImageHashes for all images already created, use ?recreate=true to force recreation.');
}

$hasher = new ImageHash(new DifferenceHash());
foreach ($resources_newhash as $ref) {
    $path = get_resource_path($ref,true,'pre');
    $hash = $hasher->hash($path);
    sql_query("UPDATE resource SET imagehash =  '$hash' WHERE ref = '$ref'");
}
$time_1 = microtime(true) - $start_1;

$time_per_hash = $time_1 / $count;
echo $count . " Resources<br>";
echo round($time_1, 4) . " Total hashtime<br>";
echo round($time_per_hash, 4) . " Hashtime per Resource<br>";