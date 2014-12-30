<?php 
require_once '_database_info.php';

class Database {    

    private $dbh;
    public $error;
    private $stmt;

    public function __construct() {
        
        $dboptions = array(
            PDO::ATTR_PERSISTENT            => true,
            PDO::MYSQL_ATTR_INIT_COMMAND    => "SET NAMES utf8",
            PDO::ATTR_ERRMODE               => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE    => PDO::FETCH_ASSOC); 
     
    
        try 
        {        
            $this->dbh = new PDO(DB_TYPE.':host='.DB_HOST.';dbname='.DB_NAME, DB_USER, DB_PASS, $dboptions);
        } 
       
            
        catch(PDOException $e)
        {
            $this->error = $e->getMessage();
            echo $this->error;
        }
    }

    public function query($query){
        $this->stmt = $this->dbh->prepare($query);
    }
 
    public function bind($param, $value, $type = null){
        if (is_null($type)) {
            switch (true) {
                case is_int($value):
                    $type = PDO::PARAM_INT;
                    break;
                case is_bool($value):
                    $type = PDO::PARAM_BOOL;
                    break;
                case is_null($value):
                    $type = PDO::PARAM_NULL;
                    break;
                default:
                    $type = PDO::PARAM_STR;
            }
        }
        $this->stmt->bindValue($param, $value, $type);
    }   

    public function execute(){
        return $this->stmt->execute();
    }

    public function all(){
        return $this->stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function single(){
        return $this->stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function row_count(){
        return $this->stmt->rowCount();
    }

    public function last_id(){
        return $this->dbh->lastInsertId();
    }

    public function begin(){
        return $this->dbh->beginTransaction();
    }

    public function commit(){
        return $this->dbh->commit();
    }

    public function revert(){
        return $this->dbh->rollBack();
    }

    public function debug_dump_params(){
        return $this->stmt->debugDumpParams();
    }


}