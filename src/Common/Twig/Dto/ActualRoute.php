<?php

namespace App\Common\Twig\Dto;

use Symfony\Component\HttpFoundation\ParameterBag;

readonly class ActualRoute {

  public function __construct(public string $name, public ParameterBag $parameters) {}
}