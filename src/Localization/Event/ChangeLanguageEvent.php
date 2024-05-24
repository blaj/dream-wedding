<?php

namespace App\Localization\Event;

use App\Localization\Enum\Localization;

readonly class ChangeLanguageEvent {

  public function __construct(public Localization $localization) {}
}