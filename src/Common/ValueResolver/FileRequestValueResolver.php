<?php

namespace App\Common\ValueResolver;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class FileRequestValueResolver implements ValueResolverInterface {

  /**
   * @return iterable<int, UploadedFile>
   */
  public function resolve(Request $request, ArgumentMetadata $argument): iterable {
    $argumentType = $argument->getType();

    if ($argumentType === null || !is_a($argumentType, UploadedFile::class, true)) {
      return [];
    }

    $argumentName = $argument->getName();

    if (!$request->files->has($argumentName)) {
      return [];
    }

    $uploadedFile = $request->files->get($argumentName);

    if (!$uploadedFile instanceof UploadedFile) {
      return [];
    }

    return [$uploadedFile];
  }
}