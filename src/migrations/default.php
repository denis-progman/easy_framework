<?php
namespace BotConstructor\ORM\Migrations;

use BotConstructor\ORM\DB;

class MigrationName extends DB {
    public function up() {
        $this->connection->exec(
            "" //sql up text
        );
    }

    public function down() {
        $this->connection->exec(
            "" //sql down text
        );
    }
}

