<?php

namespace Rawcalc;

Class Utility {

  public static function round_array($array, $round_to)
  {
    foreach ($array as $value) {
      $new_array[] = round($value, $round_to);
    }

    return $new_array;

  }
}