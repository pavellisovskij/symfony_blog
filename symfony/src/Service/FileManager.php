<?php

namespace App\Service;

use Symfony\Component\Filesystem\Exception\ExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManager
{
    private $uploadsDir;
    private $fs;
    private $dir = 'uploads/';

    public function __construct(string $uploadsDir)
    {
        $this->uploadsDir = $uploadsDir;
        $this->fs = new Filesystem();
    }

    /**
     * @param UploadedFile $file
     * @param string|null $path
     * @return string
     */
    public function upload(UploadedFile $file, string $path = null): string
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        if ($path !== null) {
            $path = $this->transformPath($path);
            $this->uploadsDir = $this->uploadsDir . $path;
        }
        
        try {
            $file->move($this->uploadsDir, $fileName);
        } catch (FileException $e) {
            dd($e->getMessage());
        }
        
        return $this->dir . $path . $fileName;
    }

    /**
     * @param string $filePath
     * @return bool
     */
    public function delete(string $filePath): bool
    {
        try {
            $this->fs->remove($this->uploadsDir . mb_substr($filePath, mb_strlen($this->dir)));
            return true;
        } catch (ExceptionInterface $e) {
            dd($e->getMessage());
        }
    }

    private function transformPath(string $path): string
    {
        if (mb_substr($path, 0, 1) !== '/') $path = '/' . $path;
        if (mb_substr($path, -1) !== '/') $path .= '/';

        return $path;
    }
}