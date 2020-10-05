<?php

declare(strict_types=1);

namespace Vendor\db {

    /**
     * Database class
     *
     * @package vendor\db
     */
    class Db
    {
        /**
         * @var object settings for connecting to the database
         */
        private $settings;

        /**
         * @var \PDO base connection, PDO object
         */
        public $conn;

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