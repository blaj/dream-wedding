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
}