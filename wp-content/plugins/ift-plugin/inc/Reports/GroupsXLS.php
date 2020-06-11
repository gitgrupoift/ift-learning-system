<?php

namespace IFT\Reports;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xls;

class GroupsXLS {
    
    public $spreadsheet;
    public $reader;
    private $path;
    
    public function __construct($path) {
        
        $this->spreadsheet = new Spreadsheet();
        $this->reader = new \PhpOffice\PhpSpreadsheet\Reader\Csv();
    }
    
    public function set($path) {
        $this->path = $path;
    }
    
    public function get($path) {
        return $this->path;
    }
    
    public function convert() {
        
        $this->reader->setDelimiter(',');
        $this->reader->setEnclosure('"');
        $this->reader->setSheetIndex(0);
        
        $this->spreadsheet = $this->reader->load($this->path);
        $writer = new Xls($spreadsheet);
        $writer->save('php://output');
        
    }

    
}