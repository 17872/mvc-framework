<?php

declare(strict_types=1);

namespace Vendor\db {

    use Vendor\kernel\Kernel;

    /**
     * Класс для работы с базой данных
     *
     * @package vendor\db
     */
    class Db extends Kernel
    {
        /**
         * @var object настроки для подключения к базе данных
         */
        private $settings;

        /**
         * @var \PDO соединение с базой, объект PDO
         */
        protected $conn;

        public function __construct()
        {
            $settings = (object)unserialize(SETTINGS);
            $this->settings = $settings->db;
            unset($settings);

            try {
                $this->conn = new \PDO(
                    'mysql: host=' . $this->settings->host . ';port=' . $this->settings->port . ';dbname=' . $this->settings->dbname . '',
                    $this->settings->user,
                    $this->settings->pass
                );
            } catch (\PDOException $e) {
                echo "Error!: " . $e->getMessage() . PHP_EOL;
                die();
            }
        }
    }
}