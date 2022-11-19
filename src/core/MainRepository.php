<?php


namespace DenisPm\EasyFramework\core;


use DenisPm\EasyFramework\orm\DB;
use Exception;

class MainRepository
{
    const TABLE_POSTFIX = 's';

    const CONNECTING_TABLE_DIVIDER = '_by_';

    public const TABLE_NAME = '';

    public static  ?DB $DB = null;

    protected string $table = '';

    protected static array $requestsCache = [];

    public function __construct(string $table = '', array $configs = [])
    {
        if (!self::$DB) {
            self::$DB = new DB($configs);
        }
        if ($table) {
            $this->table = $table;
        }
    }

    /**
     * @throws Exception
     */
    public function getData(int|string $dataId, ?string $table = null): string|array|bool|null
    {
        return self::$DB->selectOne($table ?? $this->table)
            ->makeWhere('id', '=', $dataId)
            ->send();
    }

    /**
     * @throws Exception
     */
    public function getAllData(?string $table = null): string|array|bool|null
    {
        return self::$DB->select($table ?? $this->table)->send();
    }

    /**
     * @throws Exception
     */
    public function getAllDataIntelligent(string $column, string $operator, mixed $value): array
    {
        $firstlyData = self::$DB->select($this->table)
            ->makeWhere($column, $operator, $value)
            ->send();

        foreach ($firstlyData as $keyOneFirstly => $oneFirstlyData) {
            $firstlyData[$keyOneFirstly] = $this->recursiveSelector($oneFirstlyData, self::$DB->getTables());
        }

        return $firstlyData;
    }

    /**
     * @throws \Exception
     */
    public function getLastDataIntelligent(string $column, string $operator, mixed $value): array
    {
        $firstlyData = self::$DB->select($this->table)
            ->makeWhere($column, $operator, $value)
            ->orderBy('id')
            ->limit(1)
            ->send();

        foreach ($firstlyData as $keyOneFirstly => $oneFirstlyData) {
            $firstlyData[$keyOneFirstly] = $this->recursiveSelector($oneFirstlyData, self::$DB->getTables());
        }
        return $firstlyData;
    }

    /**
     * @throws \Exception
     */
    public function getOneDataIntelligent(string $column, string $operator, mixed $value): array
    {
        $firstlyData = self::$DB->selectOne($this->table)
            ->makeWhere($column, $operator, $value)
            ->send();
        return $this->recursiveSelector($firstlyData, self::$DB->getTables());
    }

    private function recursiveSelector($firstlyData, $tables) {
        foreach ($firstlyData as $keyOneParam => $oneParam) {
            $table = "{$keyOneParam}" . self::TABLE_POSTFIX;
            if (is_numeric($oneParam) && in_array($table, $tables)) {

//                if (isset(self::$requestsCache[$cacheName = "$table-$oneParam"])) {
//                    $firstlyData[$keyOneParam] = self::$requestsCache[$cacheName];
//                } else {
//                    $firstlyData[$keyOneParam] = self::$requestsCache[$cacheName] = $this->getData($oneParam, $table);
//                }

                $firstlyData[$keyOneParam] = $this->recursiveSelector($firstlyData[$keyOneParam], $tables);
            }
            else if (
                !empty($nextTable = array_filter(
                    $tables,
                    function ($table) {
                        return str_starts_with($table, $this->table . self::CONNECTING_TABLE_DIVIDER);
                    }
                ))
            ) {
                print_r($nextTable);
                sort($nextTable);

                $table = $this->table = $nextTable[0];
                $keyOneParam = explode(self::CONNECTING_TABLE_DIVIDER, $table)[1];
                print_r($keyOneParam);

//                if (isset(self::$requestsCache[$cacheName = "$table-$oneParam"])) {
//                    $firstlyData[$keyOneParam] = self::$requestsCache[$cacheName];
//                } else {
//                    $firstlyData[$keyOneParam] = self::$requestsCache[$cacheName] = $this->getAllDataIntelligent($keyOneParam, '=', $oneParam);
//                }

                $firstlyData[$keyOneParam] = $this->recursiveSelector($firstlyData[$keyOneParam], $tables);
            }
        }
        return $firstlyData;
    }

    /**
     * @throws Exception
     */
    public function getDataBySomeThing(string $key, mixed $value): mixed
    {
        return self::$DB->selectOne($this->table)
            ->makeWhere($key, '=', $value)
            ->send();
    }

    /**
     * @throws Exception
     */
    public function updateData($dataId, $dataSet): DB
    {
        return self::$DB->update($this->table, $dataSet + ['id' => $dataId]);
    }

    /**
     * @throws Exception
     */
    public function addData($dataSet): DB
    {
        return self::$DB->insert($this->table, $dataSet);
    }


}