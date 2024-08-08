<?php

declare(strict_types=1);

namespace App;

use PDO;

use function PHPSTORM_META\type;

class Db
{
    protected static $instance = null;

    protected PDO $dbh;

    public static function instance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }

        return self::$instance;
    }


    protected function __construct()
    {
        $config = (include __DIR__ . '/config.php')['db'];
        $this->dbh = new PDO(
            'mysql:host=' . $config['host'] .
            ';dbname=' . $config['dbname'],
            $config['user'],
            $config['password']
        );
    }

    public function execute($sql, $class)
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_CLASS, $class);
    }

    public function lastId()
    {
        return $this->dbh->lastInsertId();
    }

    public function query($sql, $class, $data = [])
    {
        $sth = $this->dbh->prepare($sql);
        $sth->execute($data);

        // return $sth->fetchAll(PDO::FETCH_CLASS, $class);

        $data = $sth->fetchAll();
        $ret = [];

        foreach ($data as $row) {
            $item = new $class;

            foreach ($row as $key => $value) {
                if (is_numeric($key)) {
                    continue;
                }
                if ($value == "NULL") {
                    $value = null;
                }
                $item->$key = $value;
            }
            $ret[] = $item;
        }

        return $ret;
    }
}