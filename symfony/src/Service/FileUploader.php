<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $targetDir;
    private $filesDir;

    public function __construct(string $filesDir, string $targetDir = '../public/userfiles')
    {
        $this->targetDir = $targetDir;
        $this->filesDir = $filesDir;
    }

    public function upload(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDir(), $fileName);
        } catch (FileException $e) {
            print_r($e->getMessage());
        }

        if (mb_substr($this->getFilesDir(), -1) !== '/') $this->filesDir .= '/';

        return $this->filesDir . $fileName;
    }

    public function setDir(string $path): self
    {
        $this->targetDir .= $path;
        $this->filesDir .= $path;
        return $this;
    }

    public function getTargetDir(): string
    {
        return $this->targetDir;
    }

    public function getFilesDir(): string
    {
        return $this->filesDir;
    }
}