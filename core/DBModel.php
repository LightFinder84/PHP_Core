<?php
    abstract class DBModel{
        
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

        public function save(){
            $tableName = static::tableName();
            $columnName = implode(",", $this->attributes());

            $placeHolder = [];
            $typeList = "";
            foreach ($this->attributesValue() as $value ) {
                array_push($placeHolder, "?");
                $typeList = $typeList.self::getTypeChar($value);
            }
            $placeHolder = implode(",", $placeHolder);

            $attributes = $this->attributesValue();

            $sql = "INSERT INTO ".$tableName."(".$columnName.") VALUES (".$placeHolder.")";
            
            $statement = Application::$database->prepare($sql);
            $statement->bind_param($typeList, ...$attributes);
            return $statement->execute();
        }

        /**
         * @param conditions = ['key'=>value,...]
         */
        public static function findOne($conditions)
        {
            $tableName = static::tableName();
            $sql = "SELECT * FROM `".$tableName."` WHERE ";
            $where = [];
            $typeList = "";
            foreach ($conditions as $key => $value) {
                array_push($where, $key." =? ");
                $typeList = $typeList.self::getTypeChar($value);
            }
            $where = implode(" AND ", $where);
            $sql = $sql.$where;
            
            $statement = Application::$database->prepare($sql);
            $statement->bind_param($typeList, ...array_values($conditions));
            if($statement->execute()){
                $result = $statement->get_result();
                return $result->fetch_array();   
            }
            else return false;
        }

        protected static function getTypeChar($value){
            $type = gettype($value);
            if($type == "string"){
                return "s";
            }
            if($type == "integer"){
                return "i";
            }
            if($type == "double"){
                return "d";
            }
            return "b";
        }
    }