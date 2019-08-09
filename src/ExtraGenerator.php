<?php

namespace Rlb\MakerExtraBundle;

class ExtraGenerator
{
    public $srcPath;

    public function __construct(string $kernelRootDir)
    {
        $this->srcPath = $kernelRootDir.'/src/';
    }

    public function copyRecursivly(string $source, string $target)
    {
        $targetPath = $this->srcPath.$target;
        $skeleton = $this->getSkeletonPath($source);

        if (is_dir($targetPath)) {
            throw new \Exception(sprintf('The folder "%s" already exist in "%s"', $target, $this->srcPath));
        }

        mkdir($targetPath, 0755);

        foreach ($iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($skeleton, \RecursiveDirectoryIterator::SKIP_DOTS), \RecursiveIteratorIterator::SELF_FIRST) as $item) {
            if ($item->isDir()) {
                mkdir($targetPath.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
            } else {
                copy($item, $target.DIRECTORY_SEPARATOR.$iterator->getSubPathName());
            }
        }
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
