<?php

namespace App\Twig;

use App\Entity\Client;
use phpDocumentor\Reflection\Types\Integer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class CheckExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/2.x/advanced.html#automatic-escaping
            new TwigFilter('client', [$this, 'isClient']),
            new TwigFilter('int',[$this,'isInteger']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function isInteger($value)
    {
        return is_integer($value);
    }

    public function isClient($value)
    {
        return $value instanceof Client;
    }
}
