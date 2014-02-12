<?php
function checkAddSlashes($str) {
    //check string for \' (escaped single quote), \" (escaped double quote), \\ (escaped backslash)
    $pattern = '/\\\\[\'"\\\\]+/';
    return (!preg_match($pattern, $str)) ? addslashes($str) : $str;
}
?>