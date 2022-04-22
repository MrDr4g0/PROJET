<?php
namespace App\Service;

class InverseWordService
{
    public function inverseWord($word)
    {
        return strrev($word);
    }
}