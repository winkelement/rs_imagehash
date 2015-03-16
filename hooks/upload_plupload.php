<?php

function HookImagehashUpload_pluploadAfterpluploadfile() {
    global $ref;
    global $baseurl;
    global $imagehash_enabled;
    $res_type = sql_value("select resource_type value from resource where ref ='$ref'", '');
    if ($imagehash_enabled == true && $res_type == '1') {
        ?>
        <script>
            resourceId = '<?php echo $ref ?>';
            baseUrl = '<?php echo $baseurl ?>';
            jQuery(document).ready(function () {
                jQuery.get(baseUrl + '/plugins/imagehash/pages/update_imagehash.php', {ref: resourceId}, function (data) {
            });
        });
        </script>
        <?php
    }
}

