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
            new TwigFilter('supzero', [$this, 'supZero']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('function_name', [$this, 'doSomething']),
        ];
    }

    public function supZero($value)
    {
        return $value > 0;
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
