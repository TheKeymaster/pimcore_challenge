<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class RandomNumberGeneratorExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('random_number', [$this, 'generateRandomNumber']),
        ];
    }

    public function generateRandomNumber($min = 0, $max = 100)
    {
        return random_int($min, $max);
    }
}
