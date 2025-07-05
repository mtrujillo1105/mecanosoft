<?php
if(@$export){

    header("Content-disposition: attachment; filename=$var_name.txt");
    header("Content-Type: application/force-download");
    header("Content-Transfer-Encoding: binary");
    header("Content-Length: ".strlen($data));
    header("Pragma: no-cache");
    header("Expires: 0");
    echo $data;
}
?>