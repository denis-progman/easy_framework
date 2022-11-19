<?php
namespace BotConstructor\ORM\Migrations;

use BotConstructor\ORM\DB;

class Start extends DB {

    public function up() {
        $this->connection->exec(
            "CREATE TABLE `users` (
            `id` INT(11) AUTO_INCREMENT, 
            `telegramId` INT(1) UNIQUE NOT NULL, 
            `dateSign` TIMESTAMP, 
            `firstName` VARCHAR(100),
            `lastName` VARCHAR(100),
            `userName` VARCHAR(100) UNIQUE,
            `phone` VARCHAR(12) UNIQUE,
            `agreement` TINYINT(1),
            PRIMARY KEY (`id`)
            );
            CREATE TABLE `steps` (
            `id` INT(11), 
            `user` INT(11) UNIQUE NOT NULL, 
            `date` TIMESTAMP, 
            `rule` INT(11) NOT NULL,
            `message` INT(11),
            `callback_query` INT UNIQUE,
            `save_data` VARCHAR(1000),
            PRIMARY KEY (`id`)
            );
            ALTER TABLE `steps` ADD FOREIGN KEY (`user`) 
                REFERENCES `users`(`id`) ON DELETE RESTRICT ON UPDATE RESTRICT;"
        );
    }

    public function down() {
        $this->connection->exec(
            "DROP TABLE `users`;
                DROP TABLE `steps`;"
        );
    }
}

