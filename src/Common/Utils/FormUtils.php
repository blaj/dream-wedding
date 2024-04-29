<?php

namespace App\Common\Utils;

use App\Common\Const\FormConst;
use App\Common\Form\Type\SaveAndAddButtonType;
use Symfony\Component\Form\ClickableInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class FormUtils {

  public static function getRedirectResponse(
      FormInterface $form,
      RedirectResponse $saveRedirectResponse,
      RedirectResponse $saveAndAddRouteRedirectResponse): RedirectResponse {
    $saveAndAddButton = $form->get(FormConst::$saveAndAdd);

    return $saveAndAddButton instanceof ClickableInterface && $saveAndAddButton->isClicked()
        ? $saveAndAddRouteRedirectResponse
        : $saveRedirectResponse;
  }

  /**
   * @param array<mixed> $options
   */
  public static function isMultiple(array $options): bool {
    return array_key_exists('multiple', $options)
        && is_bool($options['multiple'])
        && $options['multiple'];
  }
}