<?php
    /**
     * Truong Pham
     */
    abstract class FormWidget{

        public const RULE_REQUIRED = 'required';
        public const RULE_EMAIL = 'email';
        public const RULE_MIN_LENGTH = 'minLength';
        public const RULE_UNIQUE = 'unique';
        public const RULE_MATCH = 'match';
        public const RULE_EXIST = 'exist';

        public $rules = [];
        public $errors = [];
        public $errorMessesges = [
            self::RULE_REQUIRED => "This field is required.",
            self::RULE_EMAIL => "This field must be a valid email address.",
            self::RULE_MATCH => "This field must be the same as __param__.",
            self::RULE_MIN_LENGTH => "This field must have at least __param__ characters.",
            self::RULE_UNIQUE => "This value has been existing in the system.",
            self::RULE_EXIST => "This __param__ does not exist in the system."
        ];

        public function __construct()
        {
            $this->setRules();
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
         * Set rule for each attributes in model
         */
        abstract public function setRules(): void;

        /**
         * Check each attributes is valid with its rules or not
         */
        public function validate(){
            // print_r("Validating data...");
            foreach ($this->rules as $attribute => $rules) {
                $value = $this->{$attribute};
                foreach ($rules as $rule ) {
                    $ruleName = (is_string($rule)) ? $rule : $rule[0];

                    if($ruleName == self::RULE_REQUIRED && (is_null($value) || $value === "")){
                        $this->addError($attribute, self::RULE_REQUIRED);
                    }
                    if($ruleName == self::RULE_EMAIL && !filter_var($value, FILTER_VALIDATE_EMAIL)){
                        $this->addError($attribute, self::RULE_EMAIL);
                    }
                    if($ruleName == self::RULE_MATCH && $value != $this->{$rule["match"]}){
                        $this->addError($attribute, self::RULE_MATCH, $rule["match"]);
                    }
                    if($ruleName == self::RULE_MIN_LENGTH && strlen($value) < $rule['length']){
                        $this->addError($attribute, self::RULE_MIN_LENGTH, (string)$rule['length']);
                    }
                    if ($ruleName == self::RULE_UNIQUE) {
                        $tableName = $rule["tableName"];
                        $sql = "SELECT * FROM `".$tableName."` WHERE ".$attribute." = ?";
                        $statement = Application::$database->prepare($sql);
                        $charType = gettype($value);
                        if($charType == "string") {$charType = "s";}
                        else if($charType == "double"){$charType = "d";}
                        else if($charType == "integer"){$charType = "i";}
                        else $charType = "b";
                        $statement->bind_param($charType, $value);
                        $statement->execute();
                        $result = $statement->get_result();
                        if($result->num_rows != 0){
                            $this->addError($attribute, self::RULE_UNIQUE);
                        }
                    }

                    if ($ruleName == self::RULE_EXIST){
                        $tableName = $rule["tableName"];
                        $sql = "SELECT * FROM `".$tableName."` WHERE ".$attribute." = ?";
                        $statement = Application::$database->prepare($sql);
                        $charType = gettype($value);
                        if($charType == "string") {$charType = "s";}
                        else if($charType == "double"){$charType = "d";}
                        else if($charType == "integer"){$charType = "i";}
                        else $charType = "b";
                        $statement->bind_param($charType, $value);
                        $statement->execute();
                        $result = $statement->get_result();
                        if($result->num_rows == 0){
                            $this->addError($attribute, self::RULE_EXIST, $attribute);
                        }
                    }
                }
            }
            return empty($this->errors);
        }

        public function addError($attribute, $rule, $param = null){
            $message = $this->errorMessesges[$rule];
            if(!is_null($param)){
                $message =  str_replace("__param__", $param, $message);
            }
            if(!isset($this->errors[$attribute])){
                $this->errors[$attribute] = $message;
            }
        }

        public function __toString() 
        {
            return '';
        }
    }