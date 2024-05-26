<?php

namespace App\Common\Utils;

use Doctrine\ORM\Query;

class DoctrineUtils {

  public static function appendToDQL(
      Query $query,
      string $dql,
      string $parameterKey,
      mixed $parameterValue,
      ?string $parameterType): void {
    $query
        ->setDQL($query->getDQL() . ' ' . $dql . ' ')
        ->setParameter($parameterKey, $parameterValue, $parameterType);
  }
}