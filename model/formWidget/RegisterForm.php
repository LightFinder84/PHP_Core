<?php
    /**
     * Truong Pham
     */
    class RegisterForm extends FormWidget{

        public $fullName;
        public $username;
        public $password;
        public $roleId;
        public $phoneNumber;
        public $email;
        public $confirmPassword;

        public function __construct()
        {
            $this->fullName = null;
            $this->username = null;
            $this->password = null;
            $this->roleId = null;
            $this->phoneNumber = null;
            $this->email = null;
            $this->confirmPassword = null;
            parent::__construct();
        }

        /**
         * Hàm này đặt các điều kiện cho các trường được submit
         */
        public function setRules(): void{
            $this->rules = [
                'fullName'=>[self::RULE_REQUIRED],
                'username' => [self::RULE_REQUIRED, [self::RULE_MIN_LENGTH, 'length'=>10], [self::RULE_UNIQUE, 'tableName'=>'user']],
                'password' => [self::RULE_REQUIRED],
                'roleId' => [self::RULE_REQUIRED],
                'phoneNumber' => [self::RULE_REQUIRED, [self::RULE_UNIQUE, 'tableName'=>'user']],
                'email' => [self::RULE_REQUIRED, self::RULE_EMAIL, [self::RULE_UNIQUE, 'tableName'=>'user']],
                'confirmPassword' => [self::RULE_REQUIRED, [self::RULE_MATCH, 'match'=>'password']]
            ];
        }
    
    }