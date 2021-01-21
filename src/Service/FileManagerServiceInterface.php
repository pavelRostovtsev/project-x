<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\UploadedFile;

interface FileManagerServiceInterface
{
    /**
     * @param UploadedFile $file
     * @return string
     */
    public function uploadImage(UploadedFile $file): string;

    /**
     * @param string $fileName
     */
    public function removeImage(string $fileName): void;

}