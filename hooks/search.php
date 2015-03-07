<?php

function HookImagehashSearchReplacesearch() {
    global $imagehash_treshold, $result, $search, $restypes, $order_by, $archive, $per_page, $offset, $sort, $starsearch, $daylimit;
    if (!stristr($search, "!similar")) {
        $result=do_search($search,$restypes,$order_by,$archive,$per_page+$offset,$sort,false,$starsearch,false,false,$daylimit, getvalescaped("go",""));
    } else {
        $search_array = explode(":", $search);
        $search_ref = $search_array[1];
        $hash_ref = sql_value("SELECT imagehash value from resource WHERE ref = '$search_ref'", '');
        $result = sql_query("SELECT *, BIT_COUNT(imagehash ^ '$hash_ref') as distance from resource "
        . "WHERE ref > '0' "
        . "AND imagehash IS NOT NULL "
        . "AND resource_type = '1' "
        . "HAVING distance < '$imagehash_treshold' "
        . "ORDER BY distance");
    }
    return $result;
}