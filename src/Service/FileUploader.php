<?php
/**
 * Created by PhpStorm.
 * User: rmdei
 * Date: 08/04/2022
 * Time: 15:48
 */

namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;


class FileUploader
{
    private $targetDirectory;
    private $slugger;

    public function __construct($targetDirectory, SluggerInterface $slugger)
    {
        $this->targetDirectory = $targetDirectory;
        $this->slugger = $slugger;
    }

    public function upload (UploadedFile $file)
    {
        $originalFilname = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilname);
        $fileName = $safeFilename.'_'.uniqid().'.'.$file->guessClientExtension();

        try {
            $file->move($this->getTargetDirectory(), $fileName);
        }
        catch (FileException $e){
            // exception
        }
        return $fileName;
    }

    public function getTargetDirectory(){
        return $this->targetDirectory;
    }
}
