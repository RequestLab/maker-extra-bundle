<?php

namespace Rlb\MakerExtraBundle;

use Symfony\Component\HttpKernel\Config\FileLocator;
use Symfony\Component\Filesystem\Filesystem;

class ExtraGenerator
{
    /**
     * @var FileLocator
     */
    private $fileLocator;

    public function __construct(
        FileLocator $fileLocator
    ) {
        $this->fileLocator = $fileLocator;
    }

    public function getRootApp(): string
    {
        $resourcePath = $this->fileLocator->locate('Kernel.php');

        return dirname($resourcePath);
    }

    public function generateFolder(string $targetPath, string $folderSource)
    {
        $skeleton = $this->getSkeletonPath($folderSource);
        $fileSystem = new Filesystem();
        $fileSystem->mirror($skeleton, $this->getRootApp().'/'.$targetPath);
    }

    private function getSkeletonPath(string $path): string
    {
        $path = __DIR__.'/Resources/skeleton/'.$path;

        if (!file_exists($path) || !is_dir($path)) {
            throw new \Exception(sprintf('Cannot find template "%s"', $path));
        }

        return $path;
    }
}
