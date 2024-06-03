<?php

namespace App\FileStorage\Service;

use App\FileStorage\Dto\DownloadFile;
use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileStorageService {

  private static string $publicPath = '/public';
  private static string $uploadsPath = '/uploads';
  private static string $postPath = '/post';

  private static string $parameterBagKey = 'kernel.project_dir';

  public function __construct(
      private readonly ParameterBagInterface $parameterBag,
      private readonly Filesystem $filesystem) {}

  public function getDownloadFile(string $path, string $originalFileName): ?DownloadFile {
    if (!$this->filesystem->exists($path)) {
      return null;
    }

    return new DownloadFile(new File($path), $originalFileName);
  }

  public function uploadFile(UploadedFile $uploadedFile, string $destinationPath): string {
    $fullDestinationPath = $this->fullPath($destinationPath);

    $this->createFolder($fullDestinationPath);
    $newFileName = md5(uniqid()) . '.' . $uploadedFile->guessExtension();
    $targetFile = $uploadedFile->move($fullDestinationPath, $newFileName);

    return $destinationPath . '/' . $targetFile->getFilename();
  }

  public function createFolderAndFile(string $path, string $fileName): string {
    $this->createFolder($path);
    $filePath = $path . '/' . $fileName;
    $this->createFile($filePath);

    return $filePath;
  }

  public function createFolder(string $path): void {
    if (!$this->filesystem->exists($path)) {
      $this->filesystem->mkdir($path);
    }
  }

  public function createFile(string $filePath): void {
    if (!$this->filesystem->exists($filePath)) {
      $this->filesystem->touch($filePath);
    }
  }

  public function saveContentToFile(string $content, string $fileName, string $path): string {
    $filePath = $this->createFolderAndFile($path, $fileName);
    file_put_contents($filePath, $content);

    return $filePath;
  }

  public function postPath(int $postId): string {
    return self::$uploadsPath
        . self::$postPath
        . '/'
        . $postId;
  }

  private function fullPath(string $path): string {
    return $this->kernelProjectDir() . self::$publicPath .  $path;
  }

  private function kernelProjectDir(): string {
    $kernelProjectDir = $this->parameterBag->get(self::$parameterBagKey);

    if (!is_string($kernelProjectDir)) {
      throw new RuntimeException();
    }

    return $kernelProjectDir;
  }
}