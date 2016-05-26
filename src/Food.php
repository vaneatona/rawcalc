<?php

namespace Rawcalc;

Class Food {

  protected $dailyWeight;
  protected $ratio = [];
  protected $actualRatio = [];

  public function __construct($dailyWeight, array $ratio = [.8, .1, .05, .05])
  {
    $this->dailyWeight = $dailyWeight;

    // ratio is meat[0], rmb[1], liver[2], organ[3]
    $ratio = $this->ratiosTotalTo100($ratio);
    $this->ratio = $ratio;
  }

  public function weightFedPerDay()
  {
    return $this->dailyWeight;
  }

  public function calculateIngredientAmounts($rmbBoneRatio = .6)
  {
    // returns associative array
    $weight = $this->calculateWeightByRatio($this->dailyWeight, $this->ratio);



    // Increase rmb amount so bone content equals per day amount
    $rmb_ingredient = $weight['bonePerDay'] / $rmbBoneRatio;

    // ratio of meat, bone + meat = 100%
    $rmbMeatRatio = 1 - $rmbBoneRatio;

    // With bone increases, so does meat unless it's 100% bone
    $rmb_meat = $rmb_ingredient * $rmbMeatRatio;

    // reduce amount of meat per day, by increase from rmb
    $meat_ingredient = $weight['meatPerDay'] - $rmb_meat;

    /** Here, if % of bone is lower than required, meat goes negative */

    $ingredientAmounts = [
      $meat_ingredient,
      $rmb_ingredient,
      $weight['liverPerDay'],
      $weight['organPerDay']
    ];

    foreach ($ingredientAmounts as $value) {
      $this->actualRatio[] = $value / $this->dailyWeight;
    }
    // var_dump($this->actualRatio);

    return Utility::round_array($ingredientAmounts, 4);
  }

  private function ratiosTotalTo100($ratio)
  {
    $result = 0;

    foreach ($ratio as $value) {
      $result += $value;
    }

    switch ($result) {
      case ($result > 1):
        // If someone gives crazy numbers, just default to standard
        $ratio = [.8, .1, .05, .05];
        break;
      case ($result === 1):
        break;
      case ($result < 1):
        $diff = 1 - $result;
        $ratio[0] += $diff;
        break;
    }

    return $ratio;
  }

  private function calculateWeightByRatio($dailyWeight, $ratio)
  {
    // Percentage of ingredients expected out of daily feeding weight
    foreach ($ratio as &$value) {
      $value *= $dailyWeight;
    }

    return [
      'meatPerDay' => $ratio[0],
      'bonePerDay' => $ratio[1],
      'liverPerDay' => $ratio[2],
      'organPerDay' => $ratio[3]
    ];
  }

  private function percentOfPercent($percentof, $percent)
  {
    // expects and returns, in decimal form
    return ($percentof * $percent);
  }

}