<?php

declare(strict_types=1);

namespace Vendor\db {

    class QueryConstructor
    {
        public $table = '';
        public $query;
        public $select = [];
        public $delete = false;
        public $where = [];
        public $orderBy = [];
        public $groupBy;
        public $limit = 0;
        public $offset = 0;

        /**
         * Forms a request 'SELECT'
         *
         * @return string
         */
        protected function getSelect(): string
        {
            if (!empty($this->select)) {
                return 'SELECT ' . implode(',', $this->select);
            }

            return 'SELECT *';
        }

        /**
         * Forms a request 'DELETE'
         *
         * @return string
         */
        protected function getDelete(): string
        {
            if ($this->delete) {
                return 'DELETE';
            }

            return '';
        }

        /**
         * Forms a request 'WHERE'
         *
         * @return string
         */
        protected function getWhere(): string
        {
            if (!empty($this->where)) {
                $arraySelect = [];
                foreach ($this->where as $key => $value) {
                    $arraySelectChild = [];
                    foreach ($value as $key1 => $value1) {
                        foreach ($value1 as $key2 => $value2) {
                            $arraySelectChild[] = $value2[0] . ' ' . $value2[1] . ' ' . "'" . $value2[2] . "'";
                        }

                        $arraySelect[] = ($key > 0 ? '(' : '') . implode(
                                ' ' . $key1 . ' ',
                                $arraySelectChild
                            ) . ($key > 0 ? ')' : '');
                    }
                }

                return ' WHERE ' . implode(' AND ', $arraySelect);
            }

            return '';
        }

        /**
         * Receives the generated request
         *
         * @return string
         */
        public function getQuery(): string
        {
            if (!empty($this->query)) {
                return $this->query;
            }

            $sql = '';
            $sql .= $this->getDelete() ?: $this->getSelect() . ' FROM' . '`' . $this->table . '`';
            $sql .= $this->getWhere();

            return $sql;
        }
    }
}