<?php

namespace App\Common\Config;

readonly class EmailConfig {

  public function __construct(public string $fromAddress, public string $fromName) {}
}