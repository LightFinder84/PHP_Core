<?php
    /**
     * Truong Pham
     */
    abstract class SqliteModel {
        abstract static function tableName(): string;
        abstract public function attributes(): array;
        abstract public function attributesValue(): array;

        public function __construct(array $data)
        {
            $this->loadData($data);
        }
        
        /**
         * Save data from Post request to property
         * input: @array $data with key and value
         */
        public function loadData(array $data) : void{
            // print_r("Loading data...");
            foreach ($data as $key => $value) {
                if(property_exists($this, $key)){
                    $this->{$key} = $value;
                }
            }
        }


        /**
         * @param conditions = ['key'=>value,...]
         */
        public static function findOne($conditions) 
        {
            $data = [];
            $tableName = static::tableName();
            $sql = "SELECT * FROM `".$tableName."` WHERE ";
            $where = [];
            $typeList = [];
            foreach ($conditions as $key => $value) {
                array_push($where, $key." = :".$key);
                $typeList[$key] = self::getTypeChar($value);
            }
            $where = implode(" AND ", $where);
            $sql = $sql.$where;
            
            $statement = Application::$database->sqlite->prepare($sql);
            foreach ($conditions as $key => $value) {
                $statement->bindValue(':'.$key, $value, $typeList[$key]);
            }
            if($result = $statement->execute()){
                while ($row = $result->fetchArray(SQLITE3_ASSOC)) {
                    array_push($data, $row);
                }  
                return $data;
            }
            else return false;
        }

        public static function findAll(){
            $tableName = static::tableName();
            $sql = "SELECT * FROM `".$tableName."`";
            $statement = Application::$database->sqlite->prepare($sql);
            if($sqliteResult = $statement->execute()){
                $result = [];
                while ($row = $sqliteResult->fetchArray(SQLITE3_ASSOC)){
                    array_push($result, $row);
                }
                return $result;
                
            }
            else return false;
        }

        protected static function getTypeChar($value){
            $type = gettype($value);
            if($type == "string"){
                return SQLITE3_TEXT;
            }
            if($type == "integer"){
                return SQLITE3_INTEGER;
            }
            if($type == "double"){
                return SQLITE3_FLOAT;
            }
            return SQLITE3_BLOB;
        }
    }