<?php

use Rawcalc\Food;

class FoodTest extends PHPUnit_Framework_TestCase
{

  protected $food;
  // protected $dailyWeight = 1.5;
  // // Assuming 1.5 lbs is used
  // protected $expectedArray = [1.1, .25, .075, .075];

  protected $dailyWeight = 1;
  // Assuming 1 lbs is used
  protected $expectedArray = [.7333, .1667, .05, .05];

  public function setUp() {
    $this->food = new Food($this->dailyWeight);
  }

  /** @test */
  function accepts_daily_weight_to_feed() {
    $this->assertEquals($this->dailyWeight, $this->food->weightFedPerDay());
  }

  /** @test */
  function calculates_ratios_of_meat_bone_liver_organ_to_feed() {
    $ingredientAmounts = $this->food->calculateIngredientAmounts();
    $this->assertEquals($this->expectedArray, $ingredientAmounts);

    $this->food = new Food(1.5);
    $ingredientAmounts = $this->food->calculateIngredientAmounts(1);
    $this->assertEquals([1.2, .15, .075, .075], $ingredientAmounts);
  }

  /** @test */
  function final_weight_equals_expected_weight_to_feed() {
    // $result = $this->food->calculateIngredientAmounts();

    $food = new Food(1, [.8, .1, 0, 0]);
    $ingredientAmounts = $food->calculateIngredientAmounts();

    $ingredientAmountsAddedUp = 0;

    foreach($ingredientAmounts as $weight) {
      $ingredientAmountsAddedUp += $weight;
    }

    $this->assertEquals($this->dailyWeight, $ingredientAmountsAddedUp);
  }

  // /** @test */
  // function if_ratios_do_not_equal_1_it_adds_meat_until_it_does() {
  //   $food = new Food(1, [.8, .1, 0, 0]);

  //   $this->assertEquals($this->dailyWeight, $resultAddedUp);
  //   $food->calculateIngredientAmounts(1);

  // }

  /** @test */
  function meals_of_pure_bone_should_still_make_sense() {
    $food = new Food(1.5, [0, 1, 0, 0]);
    $ingredientAmounts = $food->calculateIngredientAmounts(.5);

    $this->assertEquals([0, 1.5, 0, 0], $ingredientAmounts);
  }


}