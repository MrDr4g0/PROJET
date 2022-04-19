<?php

namespace App\Filter;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class md5TwigExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
        new TwigFilter('md5', [$this, 'md5Filter']),
        ];
    }

    public function md5Filter(string $password ): string
    {
        $res = md5($password);

        return $res;
    }
}