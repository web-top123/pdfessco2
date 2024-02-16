<?php

namespace App\Services;

class PdfExplode extends Pdf
{
    public function __construct()
    {
        parent::__construct();
        parent::removeNumbering();
    }

    protected function _addFile($file)
    {
        $size = $this->getTemplateSize($file);
        $this->AddPage($this->_orientation($size), $this->_getSize($size));
        $this->useTemplate($file);
    }
}
