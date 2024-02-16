<?php

namespace App\Services;

class PdfCounter extends TCPDI
{
    public function __construct()
    {
        parent::__construct();
    }

    protected function _filePath($file)
    {
        return storage_path() .'/app' .'/public/uploaded/'  . basename($file->getOriginal()["path"]);
    }

    public function count($file)
    {
        return $this->setSourceFile($this->_filePath($file));
    }
}
