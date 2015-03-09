<?php

function HookImagehashSearchReplacesearch() {
    global $imagehash_treshold, $result, $search, $restypes, $order_by, $archive, $per_page, $offset, $sort, $starsearch, $daylimit;
    if (!stristr($search, "!similar")) {
        $result=do_search($search,$restypes,$order_by,$archive,$per_page+$offset,$sort,false,$starsearch,false,false,$daylimit, getvalescaped("go",""));
    } else {
        $search_array = explode(":", $search);
        // If no reference ResourceID is provided, return null
        if (count($search_array) < 2) {
            $result = "";
            return $result;
        }
        $search_ref = $search_array[1];
        // Validate reference ResourceID
        $ref_filter_options = ["options" =>['min_range' => 1, 'max_range' => sql_value("SELECT ref value FROM resource ORDER BY ref DESC LIMIT 1", '')]];
        $ref_filtered = filter_var($search_ref, FILTER_VALIDATE_INT, $ref_filter_options);
        if (!$ref_filtered) {
            $result = "";
            return $result;
        }
        // Get imagehash value for refenrence image
        $hash_ref = sql_value("SELECT imagehash value from resource WHERE ref = '$ref_filtered'", '');
        
        //@todo check if imagehash for reference exists, create one if necessary
        
        // Calculate hamming distance from imagehashes and return results array
        $result = sql_query("SELECT *, access as user_access, BIT_COUNT(imagehash ^ '$hash_ref') as distance from resource "
        . "WHERE ref > '0' "
        . "AND imagehash IS NOT NULL "
        . "AND resource_type = '1' "
        . "HAVING distance < '$imagehash_treshold' "
        . "ORDER BY distance");
    }
    return $result;
}
