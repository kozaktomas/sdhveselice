<?php

namespace App;

use Nette\Http\FileUpload;

class GalleryManager
{

    /** @var string */
    private $galleryDirectory;

    /** @var string */
    private $coverDirectory;

    public function __construct($galleryDirectory = null, $coverDirectory = null)
    {
        if (is_null($galleryDirectory)) {
            $this->galleryDirectory = WWW_DIR . '/data/galerie';
        } else {
            $this->galleryDirectory = $galleryDirectory;
        }

        if (is_null($coverDirectory)) {
            $this->coverDirectory = WWW_DIR . '/data';
        } else {
            $this->coverDirectory = $coverDirectory;
        }
    }

    public function uploadCover(FileUpload $fileUpload)
    {
        $fileName = $fileUpload->getSanitizedName();
        $dir = $this->coverDirectory . DIRECTORY_SEPARATOR . substr(md5($fileName), 0, 1) . DIRECTORY_SEPARATOR;
        if (!is_dir($dir)) {
            mkdir($dir);
        }
        $img = $fileUpload->toImage();
        $img->resize(220, NULL);
        $img->save($dir . $fileName);
        return $fileName;
    }

    public function getCover($fileName)
    {
        return substr(md5($fileName), 0, 1) . "/" . $fileName;
    }

    public function uploadPhoto(int $article_id, FileUpload $fileUpload)
    {
        $this->createFolderStructure($article_id);
        $filename = $fileUpload->getSanitizedName();
        $image = $fileUpload->toImage();
        $image->save($this->getMainFolderPath($article_id) . DIRECTORY_SEPARATOR . $filename);
        $image->resize(183, NULL);
        $image->resize(NULL, 137);
        $image->save($this->getThumbFolderPath($article_id) . DIRECTORY_SEPARATOR . $filename);
    }

    public function getPhotos(int $article_id)
    {
        $files = glob($this->galleryDirectory . DIRECTORY_SEPARATOR . $article_id . DIRECTORY_SEPARATOR . '*.*');
        $images = [];
        if (is_array($files)) {
            foreach ($files as $image) {
                $images[] = new \SplFileInfo($image);
            }
        }

        return $images;
    }

    public function deletePhoto(int $article_id, string $name)
    {
        $main = $this->getMainFolderPath($article_id) . DIRECTORY_SEPARATOR . $name;
        $thumb = $this->getThumbFolderPath($article_id) . DIRECTORY_SEPARATOR . $name;

        if (is_file($main)) {
            unlink($main);
        }

        if (is_file($thumb)) {
            unlink($thumb);
        }
    }

    private function createFolderStructure(int $article_id)
    {
        if (!is_dir($this->getMainFolderPath($article_id))) {
            mkdir($this->getMainFolderPath($article_id));
        }

        if (!is_dir($this->getThumbFolderPath($article_id))) {
            mkdir($this->getThumbFolderPath($article_id));
        }
    }

    private function getMainFolderPath(int $article_id)
    {
        return $this->galleryDirectory . DIRECTORY_SEPARATOR . $article_id;
    }

    private function getThumbFolderPath(int $article_id)
    {
        return $this->galleryDirectory . DIRECTORY_SEPARATOR . $article_id . DIRECTORY_SEPARATOR . 'thumb';
    }

}