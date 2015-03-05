<?php
include '../../../include/db.php';
include '../../../include/authenticate.php';
include '../../../include/general.php';
require '../include/init.php';

$start_1 = microtime(true);

$resources_nohash = sql_array("SELECT ref value from resource WHERE resource_type = '1' AND preview_extension = 'jpg' ORDER BY creation_date DESC LIMIT 1000");
//var_dump($resources_nohash);
$count = count($resources_nohash);
if (!$count) {
    exit ('ImageHashes for all images already created');
}
$hasher = new ImageHash(new PerceptualHash);
foreach ($resources_nohash as $ref) {
    $path = get_resource_path($ref,true,'pre');
    $hash = $hasher->hash($path);
    sql_query("UPDATE resource SET imagehash =  '$hash' WHERE ref = '$ref'");
    //echo $path . "<br>";
    
}
$time_1 = microtime(true) - $start_1;

$time_per_hash = $time_1 / $count;
echo "<br>" . $count . " Resources<br>";
echo round($time_1, 4) . " Total hashtime<br>";
echo round($time_per_hash, 4) . " Hashtime per Resource<br>";
//$hasher = new ImageHash;
//$hash_1 = $hasher->hash(dirname(dirname(__FILE__)). '/10_24f3db5cd7a9224.jpg');
//sql_query("UPDATE resource SET imagehash =  '$hash_1' WHERE ref = '10'");

# get resource previews paths (jpg) with imagehash = null, newest first, limit to 1000

# calculate hash for all

# write hash to db

# feedback