<?php
    /**
     * Truong Pham
     */
    class LoginForm extends FormWidget{

        public $id = null;
        public $username = null;
        public $password = null;

        public function setRules(): void
        {
            $this->rules = [
                'username' => [self::RULE_REQUIRED, [self::RULE_EXIST, 'tableName'=>'user']],
                'password' => [self::RULE_REQUIRED]
            ];
        }

        public function login(){
            $user = UserModel::findOne(['username'=>$this->username]);
            $this->id = $user['id'];
            if($this->password === $user['password']){
                return true;
            }
            return false;
        }
    }