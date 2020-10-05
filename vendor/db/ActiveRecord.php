<?php

declare(strict_types=1);

namespace Vendor\db {

    use PDO;
    use Vendor\kernel\Kernel;
    use Vendor\db\QueryConstructor;

    abstract class ActiveRecord
    {
        /** @var object object child */
        protected static $child;
        /** @var QueryConstructor */
        protected static $queryConstr;
        /** @var array errors */
        public $errors = [];

        abstract public static function tableName(): string;

        /**
         * Gets the property values of an object
         *
         * @return array
         */
        protected function getObjectVars(): array
        {
            return get_object_vars(static::$child);
        }

        /**
         * Sets the generated request
         *
         * @param string $query query
         * @return object
         */
        public function query(string $query)
        {
            static::$queryConstr->query = $query;

            return static::$child;
        }

        /**
         * Forms a request 'SELECT'
         *
         * @param array $value value
         * @return object
         */
        public function select(array $value)
        {
            static::$queryConstr->select = $value;

            return static::$child;
        }

        /**
         * Forms a request 'DELETE'
         *
         * @param bool $value delete yes or no
         * @return object
         */
        public function delete(bool $value)
        {
            static::$queryConstr->delete = $value;

            return static::$child;
        }

        /**
         * Forms a request 'WHERE'
         *
         * @param array $value value
         * @return object
         */
        public function where(array $value)
        {
            static::$queryConstr->where = $value;

            return static::$child;
        }

        /**
         * Retrieves a record from a table
         *
         * @return object
         */
        public function one()
        {
            $sth = Kernel::$app->db->conn->prepare(static::$queryConstr->getQuery());
            $sth->execute();

            static::$queryConstr = null;

            return $sth->fetchObject(static::class);
        }


        /**
         * Retrieves all records of a table
         *
         * @return array
         */
        public function all(): array
        {
            $objects = [];
            $sth = Kernel::$app->db->conn->prepare(static::$queryConstr->getQuery());
            $sth->execute();
            while ($result = $sth->fetchObject(static::class)) {
                $objects[] = $result;
            }

            static::$queryConstr = null;

            return $objects;
        }

        /**
         * Searches for data in an entity table
         *
         * @return object
         */
        public static function find()
        {
            if (!isset(static::$queryConstr)) {
                static::$queryConstr = new QueryConstructor();
                static::$queryConstr->table = static::tableName();
            }
            if (!isset(static::$child)) {
                static::$child = new static();
            }

            return static::$child;
        }

        /**
         * Saves or updates entity data
         *
         * @return bool
         */
        public function save(): bool
        {
            try {
                $objectVars = $this->getObjectVars();
                $q = Kernel::$app->db->conn->prepare('DESCRIBE ' . static::tableName());
                $q->execute();
                $tableFieldsArray = [];
                $primaryKeyName = '';
                $tableFields = $q->fetchAll(PDO::FETCH_ASSOC);
                $keysSql = [];
                foreach ($tableFields as $key => $value) {
                    if (array_key_exists($value['Field'], $objectVars) && isset($objectVars[$value['Field']])) {
                        $tableFieldsArray[$value['Field']] = $objectVars[$value['Field']];
                        $keysSql[$value['Field']] = $value['Field'] . '=:' . $value['Field'];
                    }

                    if ($value['Key'] === 'PRI') {
                        $primaryKeyName = $value['Field'];
                    }
                }

                if ($primaryKeyName && !empty($tableFieldsArray[$primaryKeyName])) {
                    $sql = 'UPDATE ' . static::tableName() . ' SET ' . implode(
                            ', ',
                            $keysSql
                        ) . ' WHERE ' . $primaryKeyName . '=:' . $primaryKeyName . '';
                } else {
                    $sql = 'INSERT INTO ' . static::tableName() . ' (' . implode(
                            ', ',
                            array_keys($keysSql)
                        ) . ') VALUES (:' . implode(', :', array_keys($keysSql)) . ')';
                }

                $q = Kernel::$app->db->conn->prepare($sql);

                return $q->execute($tableFieldsArray);
            } catch (\Exception $e) {
                $this->errors[] = sprintf('[%s:%s] %s', $e->getFile(), $e->getLine(), $e->getMessage());

                return false;
            }
        }

    }
}