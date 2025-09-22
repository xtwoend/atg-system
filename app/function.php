<?php

function timeoftron($val) {
    $d = "";
    if(strlen($val) == 1) {
        $d .= "0";
    }

    $d .= $val . ":00";
    return $d;
}