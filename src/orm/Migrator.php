<?php


namespace BotConstructor\ORM;

use BotConstructor\ORM\DB;
use DenisPm\EasyFramework\core\easy\EasyHelper;

class Migrator
{
    const MIGRATIONS_DIR = 'migrations';
    const IGNORE_MIGRATIONS = [
        'default.php',
        '..',
        '.',
    ];
    protected $consoleArguments = [];
    protected $argumentsCount = 0;
    protected $migrationsFiles = [];


    public function __construct($consoleArguments)
    {
        $this->consoleArguments = $consoleArguments;
        $this->argumentsCount = count($consoleArguments);
        $this->migrationsFiles = array_diff(
            scandir(self::MIGRATIONS_DIR), self::IGNORE_MIGRATIONS
        );
        print_r($this->migrationsFiles);
    }

    public function newMigration(?string $className = null, ?array $sql = null):?string {
        $filePath = null;
        if ($this->consoleArguments[2] === 'migration' && ($className || isset($this->consoleArguments[3]))) {
            $migrationFile = time() . '_' . $className . '.php';
            $newFile = file_get_contents(self::MIGRATIONS_DIR . '/default.php');
            $newFile = str_replace(
                'MigrationName',
                $className ?? EasyHelper::toCamelCase($this->consoleArguments[3]),
                $newFile
            );
            if (is_array($sql) && isset($sql['up'], $sql['down'])) {
                $newFile = preg_replace(
                    '/\".*\"[\s]*//[\s]*sql up text/i', "\"{$sql['up']}\"", $newFile, 1
                );
                $newFile = preg_replace(
                    '/\".*\"[\s]*//[\s]*sql down text/i', "\"{$sql['down']}\"", $newFile, 1
                );
            }
            if ($filePath = file_put_contents(self::MIGRATIONS_DIR . '/' . $migrationFile, $newFile)) {
                print 'created migration ' . $migrationFile;
            }
        }
        return $filePath;
    }

    public function allMigrationsTasks($task = null){
        if (@$this->consoleArguments[2] == 'down' || $task == 'down' ) {
            $task = 'down';
            $this->migrationsFiles = array_reverse($this->migrationsFiles);
        }
        foreach ($this->migrationsFiles as $oneMigrate){
            include (self::MIGRATIONS_DIR . '/' . $oneMigrate);
            $className = self::readMigrationName($oneMigrate)[1];
            $classNameFull = "BotConstructor\\ORM\\Migrations\\$className";
            (new $classNameFull)->{$task}();
        }
    }

    public function allMigrationsUp(){
        $this->allMigrationsTasks('up');
    }

    public function oneTaskMigration() {
        $migrationsFiles = array_reverse($this->migrationsFiles);
        foreach ($migrationsFiles as $oneMigrate){
            if(strpos($oneMigrate, $this->consoleArguments[3]) === 0){
                include (self::MIGRATIONS_DIR . '/' . $oneMigrate);
                $className = self::readMigrationName($oneMigrate)[1];
                $classNameFull = "BotConstructor\\ORM\\Migrations\\$className";
                (new $classNameFull)->{$this->consoleArguments[2]}();
                print_r($this->consoleArguments[3] . " {$this->consoleArguments[2]} \n");
                break;
            }
        }
    }

    public static function readMigrationName($fileName): array {
        $fileName = substr($fileName, 0, -4);
        $words = explode('_', $fileName);
        $timestamp = $words[0];
        unset($words[0]);
        foreach ($words as &$word){
            $word = ucfirst($word);
        }
        return [$timestamp, implode('', $words)];
    }

    public function newMigrationByClass(string $class) {
        $classMethods = get_class_methods($class);
        $snakeCaseName = EasyHelper::toSnakeCase($class);
        $sql = "CREATE TABLE " . EasyHelper::toSnakeCase($class) . " (";
        foreach ($classMethods as $method) {
            if (str_starts_with($method, 'get')) {
                $field = lcfirst(str_replace('get', '', $method));
                if ($field == 'id') {

                }
                $type = EasyHelper::tryFieldType(new $class, $field);

                if ($type === 'int') {
                    $sqlType = 'INT';
                }
                elseif (class_exists(
                    ucfirst(strpos($field, 'Id') ? str_replace('Id', '', $field) : $field)
                )) {
                    $sqlType = 'INT';
                }
                elseif ($type === 'string' && str_contains($field, 'date')) {
                    $sqlType = 'TIMESTAMP';
                }
                else {
                    $sqlType = 'VARCHAR';
                }
                $sql .= "";
            }
        }
        $this->newMigration($class);

    }

    public function getArgumentsCount(): int
    {
        return $this->argumentsCount;
    }
}