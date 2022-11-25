<?php
    /**
     * Truong Pham
     */
    class m002_createUserTable {
        public function up($db){
            $sql = "CREATE TABLE `user` (
                `id` bigint(12) NOT NULL AUTO_INCREMENT PRIMARY KEY,
                `name` varchar(250) NOT NULL,
                `username` varchar(250) NOT NULL,
                `password` varchar(250) NOT NULL,
                `roleId` int(2) NOT NULL,
                `phoneNumber` bigint(11) NOT NULL,
                `email` varchar(250) NOT NULL,
                `point` bigint(10) NOT NULL DEFAULT 0,
                `avatarLink` varchar(250) DEFAULT NULL,
                FOREIGN KEY (`roleId`) REFERENCES `role`(`id`)
              ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
            return $db->query($sql);
        }

        public function down($db){
            $sql = "DROP TABLE  IF EXISTS `user`;";
            return $db->query($sql);
        }
    }