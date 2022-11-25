<?php
    /**
     * Truong Pham
     */
    class Database{

        protected mysqli $mysqli;
        protected mysqli_stmt $statement;
        protected $showErrors = true;
        protected $queryClosed = true;
        public $queryCount = 0;

        public function __construct(array $config)
        {
            $db_host = $config["db_host"];
            $db_user = $config["db_user"];
            $db_password = $config["db_password"];
            $db_name = $config["db_name"];

            $this->mysqli = new Mysqli($db_host, $db_user, $db_password, $db_name);
        }

        public function applyMigrations(){
            $this->createMigrationTable();
            $appliedMigrations =  $this->getAppliedMigration();
            // print_r($appliedMigrations);
            $files = scandir(Application::$rootPath.'/migrations');            
            $toAppliMigrations = array_diff($files, $appliedMigrations);
            $newMigrations = [];
            foreach ($toAppliMigrations as $migration ) {
                if($migration === "." || $migration === ".."){
                    continue;
                }
                require_once Application::$rootPath."/migrations/".$migration;
                $className = pathinfo($migration, PATHINFO_FILENAME);
                
                $migrationInstance = new $className();
                $this->log("Applying migration {$className}");
                array_push($newMigrations, $migration);
                if($migrationInstance->up($this)){
                    $this->saveMigrations($migration);
                }
                
            }

            if(empty($newMigrations)){
                $this->log("All migrations are applied.");
            }
        }

        public function downMigrations(){
            $appliedMigrations =  $this->getAppliedMigration();
            $downedMigration = [];
            for (end($appliedMigrations); key($appliedMigrations)!==null; prev($appliedMigrations)){
                $migration = current($appliedMigrations);
                require_once Application::$rootPath."/migrations/".$migration;
                $className = pathinfo($migration, PATHINFO_FILENAME);
                
                $migrationInstance = new $className();
                $this->log("Down migration {$className}");
                array_push($downedMigration, $migration);
                $migrationInstance->down($this);
            }
        
            if(!empty($downedMigration)){
                $this->deleteMigrations($downedMigration);
            } else {
                $this->log("All migrations are downed.");
            }
        }

        public function getAppliedMigration(){
            $statement = $this->mysqli->prepare("Select migration from migrations");
            $statement->execute();
            $mysqli_result = $statement->get_result();
            $result = [];
            while($row = $mysqli_result->fetch_row()){
                array_push($result, $row[0]);
            }
            return $result;
        }

        public function createMigrationTable(){
            $sqlStmt = "CREATE TABLE IF NOT EXISTS migrations (id INT AUTO_INCREMENT PRIMARY KEY, migration varchar(255), created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP) ENGINE=INNODB;    ";
            $this->mysqli->query($sqlStmt);
        }

        public function saveMigrations(string $migration){
            $migration = "('".$migration."')";
            $sql = "INSERT INTO migrations (migration) VALUES ".$migration;
            $statement = $this->mysqli->prepare($sql);
            $statement->execute();
        }

        public function deleteMigrations(array $downedMigration){
            // $downedMigration = array_map(fn($m)=>pathinfo($m, PATHINFO_FILENAME), $downedMigration);
            foreach ($downedMigration as $migration ) {   
                $sql = "DELETE FROM migrations WHERE migration = '".$migration."';";
                $statement = $this->mysqli->prepare($sql);
                $statement->execute();
            }
        }

        protected function log($message){
            echo '['.date('Y-m-d H:i:s').'] - '.$message.PHP_EOL;
        }

        public function query($sql){
            return $this->mysqli->query($sql);
        }

        public function multi_query($sql){
            return $this->mysqli->multi_query($sql);
        }

        public function prepare($sql){
            return $this->mysqli->prepare($sql);
        }

        public function insert_id(){
            return $this->mysqli->insert_id;
        }
    }