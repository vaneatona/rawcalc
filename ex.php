<?php

use Rawcalc\Food;

$food = new Food(589.67);

var_dump($food->calculateIngredientAmounts(.6));

// var_dump($food->percentOfPercent(.70, .80));