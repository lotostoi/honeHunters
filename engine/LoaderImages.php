<?php

namespace app\engine;

class LoaderImages
{
    protected $load = null;

    public function __construct()
    {
        $this->Resize = new SimpleImage();
    }

    public function loadSmallBigImg($fieldInFiles, $linkToBigFolder, $linkToSmallFolder)
    {
        $name = false;
        $file =  $this->getInfoFile($fieldInFiles);
        if ($file['name'] !== '') {
            $name = uniqid() . "." . explode('/', $file['ext'])[1];
            $link = $file['link'];
            if (move_uploaded_file($link, $linkToBigFolder . $name)) {
                $this->Resize->load($linkToBigFolder . $name);
                $this->Resize->resizeToWidth(600);
                $this->Resize->save($linkToSmallFolder . $name);
            } else {
                $name = false;
            }
        }
        return $name;
    }

    public function ResizeImg($file, $linkToBigFolder, $linkToSmallFolder)
    {
        $name = explode('/', $file)[count(explode('/', $file)) - 1];
        $this->Resize->load($file);
        $this->Resize->resize(720, 476);
        $this->Resize->save($linkToBigFolder . $name);
        $this->Resize->load($file);
        $this->Resize->resize(400, 272);
        $this->Resize->save($linkToSmallFolder . $name);
        $folder = App::call()->config['STOR_FOR_IMG'];
        App::call()->LoaderImages->cleanDir($folder);
        return $name; 
    }
    
    public function resImage($img, $folder, $width) {
        $name = explode('/', $img)[count(explode('/', $img)) - 1];
        $this->Resize->load($img);
        $this->Resize->resizeToWidth($width);
        $this->Resize->save($folder . $name);
    }

    public function loadImage($img, $folder)
    {
        $name = uniqid() . "." . explode('.', $img['name'])[1];
        App::call()->LoaderImages->cleanDir($folder);
        move_uploaded_file($img['tmp_name'], $folder . $name);
        return $folder . $name;
    }

    // $fieldInFiles velue of attribut "name" of input:file
    public function getInfoFile($fieldInFiles)
    {
        $file = null;
        $f = $_FILES[$fieldInFiles];
        if ($f['name'][0] !== '') {
            $file = [
                'name' => $f['name'][0],
                'short_name' => preg_replace('/.(jpg|jpeg|png)/', '', $f['name'][0]),
                'ext' => $f['type'][0],
                'link' => $f['tmp_name'][0],
                'size' => $f['size'][0],
            ];
        }
        return $file;
    }

    public function cleanDir($dir)
    {
        $files = glob($dir . "/*");
        if (count($files) > 0) {
            foreach ($files as $file) {
                if (file_exists($file)) {
                    unlink($file);
                }
            }
        }
    }
}
