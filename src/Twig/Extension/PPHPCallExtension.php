<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class PPHPCallExtension extends AbstractExtension
{
    public function getFunctions()
    {
        return [
            new TwigFunction('static_call', [$this, 'staticCall']),
        ];
    }

    public function staticCall($class, $function, $args = [])
    {
        if (class_exists($class) && method_exists($class, $function)) {
            return call_user_func_array([$class, $function], $args);
        }

        dd('failed here');

        return null;
    }

    public function getName()
    {
        return 'php_call_extension';
    }
}
