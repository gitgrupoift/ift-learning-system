<?php

namespace IFT\Reports;

class SQLite {
    
    /**
     * Instância PDO
     * @var type 
     */
    private $pdo;
    
    public function __construct($name) {
        
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:" . IFT_REPORTS . $name . ".sqlite");
        }
    
        return $this->pdo;
        
    }
    
}