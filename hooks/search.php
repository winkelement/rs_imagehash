<?php

spl_autoload_register(function ($class) {
include '../plugins/imagehash/lib/imagehash/' . $class . '.php';
});

function HookImagehashSearchReplacesearchresult() {
    global $ref, $result, $search, $restypes, $order_by, $archive, $per_page, $offset, $sort, $starsearch, $daylimit;
    if (!stristr($search, "!similar")) {
        $result=do_search($search,$restypes,$order_by,$archive,$per_page+$offset,$sort,false,$starsearch,false,false,$daylimit, getvalescaped("go",""));
    } else {
        $search_array = explode(":", $search);
        $imagehash_ref = $search_array[1];
        $hash_ref = sql_value("SELECT imagehash value from resource WHERE ref = '$imagehash_ref'", '');
        $hashes = sql_query("SELECT ref, imagehash from resource WHERE ref > '0'");
        $hasher = new ImageHash;
        $n = 0;
        foreach ($hashes as $value) {
                $distance = $hasher->distance($hash_ref, $value['imagehash']);
                if ($distance < 15) {
                    $hash_results[$n] = ['ref' => $value['ref'], 'distance' => $distance];
                    $n++;
                }
        }
        $i = 0;
        foreach ($hash_results as $key => $value) {
            $ref = $value['ref'];
            $results = sql_query("SELECT * from resource WHERE ref = '$ref'");
            $result[$i] = $results[0];
            $result[$i]['distance'] = $value['distance'];
			$distances[$key] = $value['distance'];
            $i++; 
        }
		array_multisort($distances, SORT_ASC, $result);
    }
    return $result;
    
}