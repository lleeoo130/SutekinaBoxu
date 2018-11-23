<?php

namespace App;


trait ArrayRemoveKeyTrait
{

    function array_remove_keys($array, $keys) {

        // array_diff_key() expected an associative array.
        $assocKeys = array();
        foreach($keys as $key) {
            $assocKeys[$key] = true;
        }

        return array_diff_key($array, $assocKeys);
    }
}