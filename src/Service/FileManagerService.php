<?php


namespace App\Service;

use App\Service\FileManagerServiceInterface;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileManagerService implements FileManagerServiceInterface
{
    private $imageDirectory;

    /**
     * FileManagerService constructor.
     * @param $imageDirectory
     */
    public function __construct($imageDirectory)
    {
        $this->imageDirectory = $imageDirectory;
    }

    /**
     * @return mixed
     */
    public function getImageDirectory()
    {
        return $this->imageDirectory;
    }

    /**
     * @param UploadedFile $file
     * @return string
     */
    public function uploadImage(UploadedFile $file): string
    {
        $fileName = uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getImageDirectory(), $fileName);
        } catch (FileException $exception) {
            return $exception;
        }

        return $fileName;
    }

    public function removeImage(string $fileName): void
    {
        $fileSystem = new Filesystem();
        $fileImage = $this->getImageDirectory().''.$fileName;
        try {
            $fileSystem->remove($fileImage);
        } catch (IOExceptionInterface $exception){
            echo $exception->getMessage();
        }
    }

}