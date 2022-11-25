<?php
    /**
     * Truong Pham
     */
    class UserModel extends DBModel{

        public $id;
        public $username;
        public $password;
        public $roleId;
        public $phoneNumber;
        public $email;
        public $point;
        public $avatarLink;

        public function __construct(array $data)
        {
            $this->id = null;
            $this->username = null;
            $this->password = null;
            $this->roleId = null;
            $this->phoneNumber = null;
            $this->email = null;
            $this->point = 0;
            $this->avatarLink = null;
            $this->confirmPassword = null;
            parent::__construct($data);
        }

        static function tableName(): string
        {
            return "user";
        }

        public function attributes(): array
        {
            return ['id', 'username', 'password', 'roleId', 'phoneNumber', 'email', 'point', 'avatarLink'];
        }

        public function attributesValue(): array
        {
            return [
                $this->id,
                $this->username,
                $this->password,
                $this->roleId,
                $this->phoneNumber,
                $this->email,
                $this->point,
                $this->avatarLink
            ];
        }
    
    }