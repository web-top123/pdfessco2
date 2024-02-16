<?php

namespace App\Services;
use Illuminate\Support\Str;

class Pdf extends TCPDI
{
    protected $_footer;
    protected $_header;
    protected $_items = [];
    protected $_template;
    protected $_skip = [];
    protected $_numbering = true;

    public function __construct()
    {
        parent::__construct();

        $this->setSourceFile(storage_path(config('pdf.template')));
        $this->_template = $this->importPage(1);
    }

    public function Footer()
    {

        if (!in_array($this->page, $this->_skip)) {

            if ($this->_footer) {

                $this->SetY(-config('pdf.footer.margin-bottom'));
                $this->SetTextColor(config('pdf.footer.color.r'), config('pdf.footer.color.g'), config('pdf.footer.color.b'));
                $this->SetFont( config('pdf.font-family'), '', config('pdf.footer.font-size'));
                $this->Write(config('pdf.footer.line-height'), $this->_footer);

                $this->SetLineWidth(config('pdf.footer.border-width'));
                $this->SetDrawColor(config('pdf.footer.color.r'), config('pdf.footer.color.g'), config('pdf.footer.color.b'));
                $this->Line(
                    config('pdf.footer.margin-side'),
                    $this->_marginBottom(config('pdf.footer.margin-bottom') + 1.5),
                    $this->_marginRight(config('pdf.footer.margin-side')),
                    $this->_marginBottom(config('pdf.footer.margin-bottom') + 1.5)
                );

                if ($this->_numbering) {

                    $this->SetX($this->GetPageWidth() - 32.5);
                    $this->Write(config('pdf.footer.line-height'), $this->getAliasNumPage() . '/' . $this->getAliasNbPages());
                }

            } else {

                if ($this->_numbering) {

                    $this->SetY(-config('pdf.footer.margin-bottom'));
                    $this->SetX($this->GetPageWidth()/2 - 3);
                    $this->SetFont( config('pdf.font-family'), '', config('pdf.footer.font-size'));
                    $this->Write(config('pdf.footer.line-height'), $this->getAliasNumPage() . '/' . $this->getAliasNbPages());
                }


            }
        }
    }

    public function Header()
    {
        if (!in_array($this->page, $this->_skip)) {

            if ($this->_header) {

                $this->SetY(config('pdf.header.margin-top'));
                $this->SetTextColor(config('pdf.header.color.r'), config('pdf.header.color.g'), config('pdf.header.color.b'));
                $this->SetFont(config('pdf.font-family'), '', config('pdf.header.font-size'));
                $this->Write(config('pdf.header.line-height'), $this->_header);

                $this->SetLineWidth(config('pdf.header.border-width'));
                $this->SetDrawColor(config('pdf.header.color.r'), config('pdf.header.color.g'), config('pdf.header.color.b'));
                $this->Line(
                    config('pdf.header.margin-side'),
                    config('pdf.header.margin-top') + 6,
                    $this->_marginRight(config('pdf.header.margin-side')),
                    config('pdf.header.margin-top') + 6
                );
            }
        }
    }

    public function addCover($cover)
    {
        $this->_skip[] = $this->PageNo() + 1;

        $this->AddPage('P', 'LETTER');
        $this->useTemplate($this->_template);
        $this->SetY(config('pdf.cover.margin-top'));

        $this->SetTextColor(config('pdf.cover.title.color.r'), config('pdf.cover.title.color.g'), config('pdf.cover.title.color.b'));
        $this->SetFont(config('pdf.cover.title.font-family'), '', config('pdf.cover.title.font-size'));
        $this->setCellHeightRatio(1.5);
        $this->setCellPadding(6, 0, 6, 0);
        $this->MultiCell(0, 0, $cover['projectType'], 0, 'C', 0, '');

        $this->SetY(config('pdf.cover.margin-top') + 30);
        $this->SetTextColor(config('pdf.cover.title.color.r'), config('pdf.cover.title.color.g'), config('pdf.cover.title.color.b'));
        $this->SetFont(config('pdf.cover.title.font-family'), '', config('pdf.cover.title.font-size'));
        $this->setCellHeightRatio(1.5);
        $this->setCellPadding(6, 0, 6, 0);
        $this->MultiCell(0, 0, strtoupper('Project Name:'), 0, 'C', 0, '');

        $this->SetY(config('pdf.cover.margin-top') + 40);
        $this->SetTextColor(config('pdf.cover.color.r'), config('pdf.cover.color.g'), config('pdf.cover.color.b'));
        $this->SetFont(config('pdf.cover.font-family'), '', config('pdf.cover.font-size'));
        $this->setCellHeightRatio(1.5);
        $this->setCellPadding(6, 0, 6, 0);
        $this->MultiCell(0, 0, $cover['project'], 0, 'C', 0, '');

        $this->SetY(config('pdf.cover.margin-top') + 80);
        $this->SetTextColor(config('pdf.cover.title.color.r'), config('pdf.cover.title.color.g'), config('pdf.cover.title.color.b'));
        $this->SetFont(config('pdf.cover.title.font-family'), '', config('pdf.cover.title.font-size'));
        $this->setCellHeightRatio(1.5);
        $this->setCellPadding(6, 0, 6, 0);
        $this->MultiCell(0, 0, strtoupper('Customer Name:'), 0, 'C', 0, '');

        $this->SetY(config('pdf.cover.margin-top') + 90);
        $this->SetTextColor(config('pdf.cover.color.r'), config('pdf.cover.color.g'), config('pdf.cover.color.b'));
        $this->SetFont(config('pdf.cover.font-family'), '', config('pdf.cover.font-size'));
        $this->setCellHeightRatio(1.5);
        $this->setCellPadding(6, 0, 6, 0);
        $this->MultiCell(0, 0, $cover['customer'], 0, 'C', 0, '');

        $this->SetY(config('pdf.cover.margin-top') + 130);
        $this->SetTextColor(config('pdf.cover.title.color.r'), config('pdf.cover.title.color.g'), config('pdf.cover.title.color.b'));
        $this->SetFont(config('pdf.cover.title.font-family'), '', config('pdf.cover.title.font-size'));
        $this->setCellHeightRatio(1.5);
        $this->setCellPadding(6, 0, 6, 0);
        $this->MultiCell(0, 0, strtoupper('Essco Job #:'), 0, 'C', 0, '');

        $this->SetY(config('pdf.cover.margin-top') + 140);
        $this->SetTextColor(config('pdf.cover.color.r'), config('pdf.cover.color.g'), config('pdf.cover.color.b'));
        $this->SetFont(config('pdf.cover.font-family'), '', config('pdf.cover.font-size'));
        $this->setCellHeightRatio(1.5);
        $this->setCellPadding(6, 0, 6, 0);
        $this->MultiCell(0, 0, $cover['job'], 0, 'C', 0, '');
     }

    public function addFile($file, $selectedPages)
    {
        $pages = $this->setSourceFile($this->_filePath($file)) + 1;
        for ($page = 1; $page < $pages; ++$page) {
            if (in_array($page, $selectedPages)) {
                $this->_items[] = ['type' => 'file', 'template' => $this->importPage($page)];
            }
        }
    }

    public function addDivider($divider)
    {
        $this->_items[] = ['type' => 'divider', 'text' => $divider];
    }

    public function save()
    {
        $this->_assemble();
        return $this->_store($this->_generatePath());
    }


    public function addFooter($footer)
    {
        $this->_footer = $footer;
    }

    public function addHeader($header)
    {
        $this->_header = $header;
    }

    public function removeNumbering()
    {
        $this->_numbering = false;
    }

    protected function _addDivider($divider)
    {
        $this->_skip[] = $this->PageNo() + 1;

        $this->AddPage('P', 'LETTER');
        $this->useTemplate($this->_template);
        // $this->SetY(config('pdf.divider.margin-top'));
        $this->SetY($this->GetPageHeight() / 2 - config('pdf.divider.margin-top'));
        $this->SetTextColor(config('pdf.divider.color.r'), config('pdf.divider.color.g'), config('pdf.divider.color.b'));
        $this->SetFont(config('pdf.font-family'), '', config('pdf.divider.font-size'));
        $this->setCellHeightRatio(config('pdf.divider.line-height') / 10);
        $this->MultiCell(0, 0, $divider, 0, 'C', 0, '');
        // $this->Write(config('pdf.divider.line-height'), $divider);
    }

    protected function _addFile($file)
    {
        $size = $this->getTemplateSize($file);
        $this->AddPage($this->_orientation($size), $this->_getSize($size));
        $this->StartTransform();

        // $h = $size['h'] - (config('pdf.header.height') + config('pdf.header.height'));
        $hNew = config('pdf.header.height') + config('pdf.header.height');
        $h = $size['h'] - $hNew;

        $p = $h * 100 / $size['h'];
        $w = $p * $size['w'] / 100;
        $wNew = $size['w'] - $w;

        $this->ScaleX($p);
        $this->ScaleY($p);

        $this->useTemplate($file, $hNew / 2, $wNew / 2, null, null, false);
        $this->StopTransform();
    }

    protected function _assemble()
    {
        foreach ($this->_items as $item) {

            if ($item['type'] === 'file') {

                $this->_addFile($item['template']);
                continue;
            }

            if ($item['type'] === 'divider') {

                $this->_addDivider($item['text']);
                continue;
            }
        }
    }

    protected function _filePath($file)
    {
        return storage_path() .'/app' .'/public/uploaded/'  . basename($file->getOriginal()["path"]);
    }

    protected function _generatePath()
    {
        return 'public/' . config('pdf.folder') . '/' . Str::random(40) . '.pdf';
        // return'public/' . config('pdf.folder') . '/' . 'test' . '.pdf';
    }

    protected function _getSize($size)
    {
        return [
            0 => $size['h'],
            1 => $size['w'],
        ];
    }

    protected function _marginBottom($margin)
    {
        return $this->GetPageHeight() - $margin;
    }

    protected function _marginRight($margin)
    {
        return $this->GetPageWidth() - $margin;
    }

    protected function _orientation($size)
    {
        return $size['w'] > $size['h'] ? 'L' : 'P';
    }

    protected function _store($path)
    {
        \Storage::put($path, $this->Output('', 'S'));
        return $path;
    }

}
