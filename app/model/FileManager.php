<?php

namespace App\Model;

use Nette\Http\FileUpload;

class FileManager
{

    /** @var string */
    private $directory;

    /**
     * @param string $dir
     */
    public function __construct(string $dir = null)
    {
        if ($dir === null) {
            $this->directory = __DIR__ . '/../../www/manager';
        } else {
            $this->directory = $dir;
        }
    }

    /**
     * @return \SplFileInfo[]
     */
    public function getAllFiles()
    {
        $files = [];
        foreach (glob($this->directory . "/*.*") as $file) {
            $files[] = new \SplFileInfo($file);
        }

        return $files;
    }

    public function uploadFile(FileUpload $fileUpload)
    {
        $fileUpload->move($this->directory . DIRECTORY_SEPARATOR . $fileUpload->getSanitizedName());
    }

    public function deleteFile($fileName)
    {
        $filePath = $this->directory . DIRECTORY_SEPARATOR . $fileName;
        if (is_file($filePath)) {
            unlink($filePath);
            return true;
        }

        return false;
    }

}