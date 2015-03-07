<?php

function HookImagehashViewAfterresourceactions (){
    global $imagehash_enabled, $ref, $lang, $baseurl_short, $resourcetoolsGT;
    if ($imagehash_enabled) {
        ?>
        <li><a onClick='return CentralSpaceLoad(this,true);' href='/pages/search.php?search=!similar:<?php echo $ref?>'>
        <?php echo ($resourcetoolsGT?"&gt; ":"").$lang['find_similar'];?>
        </a></li>
        <?php
    }
}
