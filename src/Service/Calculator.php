<?php
namespace App\Service;

class Calculator
{
    /**
     *
     */
    public function applyInterestFee($price, $fee)
    {
        return ($price * (1 + ($fee / 100)));
    }

    /**
     *
     */
    public function applyDiscount($price, $discount)
    {
        return ($price * (1 - ($discount / 100)));
    }
}
