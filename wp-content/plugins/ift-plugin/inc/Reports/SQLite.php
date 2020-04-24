<?php

namespace IFT\Reports;

class SQLite {
    
    /**
     * Instância PDO
     * @var type 
     */
    private $pdo;
     /**
     * Ficheiro de dados
     * @var type 
     */
    private $filename;

    
    public function __construct($name) {
        
        if ($this->pdo == null) {
            $this->pdo = new \PDO("sqlite:" . IFT_REPORTS . $name . ".sqlite");
        }
        return $this->pdo;
        
    }
    
    public static function create_page_table( $user_id, $page_id, $time ) {
        
        $access = new \PDO("sqlite:" . IFT_REPORTS . $user_id . ".sqlite");
        
        $access->exec("CREATE TABLE IF NOT EXISTS messages (
                    id INTEGER PRIMARY KEY, 
                    " . $page_id . " INTEGER,
                    " . $time . " INTEGER)");
    }
    
    public static function update() {}
    
    public static function delete() {}
    
}