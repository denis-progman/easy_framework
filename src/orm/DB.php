<?php


namespace DenisPm\EasyFramework\orm;

use DenisPm\EasyFramework\core\ModelFather;
use Exception;
use PDO;
use PDOStatement;

/**
 * Class DB
 * @package BotConstructor\ORM
 */
class DB extends ModelFather
{

    protected static bool $debug = true;

    protected ?PDO $connection = null;

    protected ?PDOStatement $request = null;

    protected static ?array $requestsCache = null;

    protected static ?array $config = null;

    private const SQL_PARAMS_DELIMITER = ", ";

    private string $sqlString = '';

    private string $stringSet = '';

    private array $paramsSet = [];

    private string $limitString = '';

    private mixed $lastResult = null;

    private ?int $updateId  = null;

    private ?string $requestType = null;

    private bool $sendWithOutRequest = false;
    private mixed $dataWithOutRequest = null;

    const ONE_SELECT_TYPE_REQUEST = 'select_one';
    const SELECT_TYPE_REQUEST = 'select';
    const UPDATE_TYPE_REQUEST = 'update';
    const INSERT_TYPE_REQUEST = 'insert';

    /**
     * DB constructor.
     * @throws Exception
     */
    public function __construct(array $configs = [])
    {
        self::builder();
        $this->makeConnection($configs);
    }

    public function resetParams():void {
        $this->sqlString =
            $this->stringSet =
                $this->limitString = '';
        $this->requestType = null;
        $this->paramsSet = [];
    }

    /**
     * @return PDO|null
     */
    public function getConnection(): ?PDO
    {
        return $this->connection;
    }

    /**
     * @param array $configs
     * @return DB
     * @throws Exception
     */
    public function makeConnection(array $configs = []): self
    {
        try {
            if (empty($configs)) {
                $configs = static::$config;
            }
            $this->connection = new PDO(
                "mysql:dbname={$configs['name']}" .
                ";host={$configs['host']}" .
                (@$configs['port'] ? (';port=' . $configs['port']) : ''),
                $configs['user'],
                $configs['password']
            );
            $this->connection->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->connection->exec("set names utf8");
            $this->connection->exec("SET SESSION group_concat_max_len = 1000000");
            $this->connection->exec("SET sql_mode=''");
            return $this;
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }
    }

    /**
     * @param array $configs
     * @return DB
     * @throws Exception
     */
    public function setConfigs(array $configs): self
    {
        static::$config = $configs;
        $this->makeConnection($configs);
        return $this;
    }

    /**
     * @return array|null
     */
    public function getConfigs(): ?array
    {
        return static::$config;
    }

    /**
     * @throws Exception
     */
    public function select(string $table, array $selectStack = []): self // запрос к базе
    {
        $this->resetParams();
        $selectStackString = !empty($selectStack) ? implode(', ', $selectStack) : '*';
        $this->sqlString = "SELECT {$selectStackString} FROM `{$table}` {$this->stringSet}";
        $this->requestType = self::SELECT_TYPE_REQUEST;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function selectOne(string $table, array $selectStack = []): self //запрос одной строчки
    {
        $this->select($table, $selectStack)->limit(1);
        $this->requestType = self::ONE_SELECT_TYPE_REQUEST;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function insert(string $table, array $params): self // для insert и update
    {
        $this->resetParams();
        if (!empty($params)) {
            $this->prepareParamsSet($params);
        }
        $this->sqlString = "INSERT `{$table}` SET {$this->stringSet}";
        $this->requestType = self::INSERT_TYPE_REQUEST;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function count(string $table): self // Выводит количество записей
    {
        $this->resetParams();
        $this->sqlString = "SELECT `COUNT(*)` AS 'count' FROM `{$table}` {$this->stringSet}";
        $this->requestType = self::ONE_SELECT_TYPE_REQUEST;
        return $this;
    }

    public function getRows(): mixed // Выводит кол-во строк другим способом
    {
        $request = $this->connection->query('SELECT FOUND_ROWS() as num');
        return $request->fetchColumn();
    }

    public function getTables(): array // все таблицы базы
    {
        $request = $this->connection->query('SHOW TABLES');
        return array_map(function ($v){
            return $v[0];
        }, $request->fetchAll(PDO::FETCH_NUM));
    }

    public function getInsertId(): ?int // Последнйи автоинкриментный ID
    {
        $lastInsertId = $this->connection->lastInsertId();
        return ($lastInsertId !== false) ? $lastInsertId : null;
    }

    /**
     * @throws Exception
     */
    public function update(string $table, array $params = []): self
    {
        $this->resetParams();
        if (!empty($params)) {
            $this->prepareParamsSet($params);
        }
        $this->sqlString = "UPDATE `{$table}` SET {$this->stringSet}";
        $this->requestType = self::ONE_SELECT_TYPE_REQUEST;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function IUBySomeThing(string $table, array $insertParams, array $majorKeys = []): self {
        $this->resetParams();
        $request = @$this->select($table);
        $majorCounter = 0;
        $majorKey = null;
        foreach ($majorKeys as $oneMajorKey) {
            if (!array_key_exists($oneMajorKey, $insertParams)) {
                continue;
            }
            if (!$majorCounter) {
                $request->makeWhere($oneMajorKey, '=', $insertParams[$oneMajorKey]);
                $majorKey = $oneMajorKey;
            } else {
                $request->orWhere($oneMajorKey, '=', $insertParams[$oneMajorKey]);
            }
            $majorCounter++;
        }

        $data = @$request->send()[0];

        if (isset($data['id']) && $data['id'] && $majorKey) {
            $this->updateId = $data['id'];
//            if (md5(serialize($insertParams)) == md5(serialize($data))) {
//                $this->sendWithOutRequest = true;
//                $this->dataWithOutRequest = $data['id'];
//                return $this;
//            }
            return $this->update($table, $insertParams)
                ->makeWhere($majorKey, '=', $insertParams[$majorKey]);
        }
        return $this->insert($table, $insertParams);
    }

    /**
     * @throws Exception
     */
    public function prepareParamsSet(array $requestParams): self
    {
        if (!empty($requestParams)) {
            foreach ($requestParams as $keyParam => $oneParam) {
                $this->stringSet .= " `$keyParam` = ?" . self::SQL_PARAMS_DELIMITER;
                $this->paramsSet[] = $oneParam;
            }
            if ($this->stringSet) {
                $this->stringSet = substr($this->stringSet, 0, -2);
            }
        } else {
            throw new Exception("Empty array requestParams");
        }

        return $this;
    }

    /**
     * @throws Exception
     */
    public function makeWhere(string $param, string $operator, int|string|float|null $value): self
    {
        if (!$this->sqlString) {
            throw new Exception("Adding where conditions to empty request!");
        }
        $this->sqlString .= " WHERE `{$param}` {$operator} ?";
        $this->paramsSet[] = $value;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function orderBy(string $param, string $direction = 'DESC'): self
    {
        if (!$this->sqlString) {
            throw new Exception("Adding where conditions to empty request!");
        }
        $this->sqlString .= " ORDER BY `$param` $direction";
        return $this;
    }

    /**
     * @throws Exception
     */
    public function andWhere(string $param, string $operator, int|string|float|null $value): self
    {
        if (!stripos($this->sqlString, "where")) {
            throw new Exception("Adding new where without 'where' instruction!");
        }
        $this->sqlString .= " AND `{$param}` {$operator} ?";
        $this->paramsSet[] = $value;
        return $this;
    }

    public function limit(int $count, ?int $offset = null): self
    {
        $this->limitString = " LIMIT $count" . ($offset ? ", $offset" : "");
        return $this;
    }

    /**
     * @throws Exception
     */
    public function orWhere(string $param, string $operator, int|string|float $value): self
    {
        if (!stripos($this->sqlString, "where")) {
            throw new Exception("Adding new where without 'where' instruction!");
        }
        $this->sqlString .= " OR `{$param}` {$operator} ?";
        $this->paramsSet[] = $value;
        return $this;
    }

    /**
     * @throws Exception
     */
    public function send(): string|array|bool|null
    {
        return self::sqlErrorChecker(function () {
            $cacheName = md5($this->sqlString . print_r($this->paramsSet, true));
            if (!$this->sendWithOutRequest){
                if ($cacheName && isset(self::$requestsCache[$cacheName])) {
                    return self::$requestsCache[$cacheName];
                }
                $this->sqlString = $this->sqlString . $this->limitString;
                $this->request = $this->connection->prepare($this->sqlString);
                if (!$this->request->execute($this->paramsSet)) {
                    throw new Exception("Error while DB request");
                }
            }
            else {
                $this->sendWithOutRequest = false;
                return $this->dataWithOutRequest;
            }
            return match ($this->requestType) {
                self::ONE_SELECT_TYPE_REQUEST => self::$requestsCache[$cacheName] = $this->lastResult = $this->request->fetch(PDO::FETCH_ASSOC),
                self::SELECT_TYPE_REQUEST => self::$requestsCache[$cacheName] = $this->lastResult = $this->request->fetchAll(PDO::FETCH_ASSOC),
                self::INSERT_TYPE_REQUEST => self::$requestsCache[$cacheName] = $this->connection->lastInsertId(),
                self::UPDATE_TYPE_REQUEST =>  self::$requestsCache[$cacheName] = $this->updateId,
                default => true,
            };
        });
    }

    protected static function prepareMsSqlQueryParams(&$query, &$params): void // для повторных именованых параметров в одном запросе
    {
        foreach ($params as $key_param => $one_param) {
            $pattern = "/(:$key_param)/";
            $count = 0;
            $query = preg_replace_callback($pattern, function ($matches) use (&$count, &$new_params, &$params){
                $count ++;
                $added_params = $matches[1] . $count;
                $params[$added_params] = $params[$matches[1]];
                return $added_params;
            }, $query);
            unset($params[$key_param]);
        }
    }


    /**
     * @throws Exception
     */
    protected static function sqlErrorChecker(callable $checkFunction):mixed {
        try {
            return $checkFunction();
        }
        catch(Exception $e) {
            if($_SERVER['SERVER_NAME'] == "localhost" || self::$debug)
            {
                echo "<pre>";
                print_r($e);
                exit();
            }
            throw new Exception("Произошла ошибка при работе с БД. Обратитесь к администратору");
        }
    }
}