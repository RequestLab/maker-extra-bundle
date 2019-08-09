<?php

namespace Rlb\MakerExtraBundle;

use Symfony\Bundle\MakerBundle\Generator;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpKernel\Config\FileLocator;

class ExtraGenerator extends Generator
{
    /**
     * @var FileLocator
     */
    private $fileLocator;

    public function setFileLocator(FileLocator $fileLocator): void
    {
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
