<?php

declare(strict_types=1);

namespace App\accounts\entities {

    use Vendor\db\ActiveRecord;

    /**
     * Entity for working with accounts
     *
     * @package App\accounts\entities
     */
    class Accounts extends ActiveRecord
    {
        /**
         * @inheritDoc
         */
        public static function tableName(): string
        {
            return 'accounts';
        }

        /** @var int primary key */
        private $id;
        /** @var float account number */
        private $number;
        /** @var string account status */
        private $status;
        /** @var string time of creation */
        private $date;
        /** @var float discount */
        private $discount;
        /** @var string account composition */
        private $account_composition;

        public function setId($value): Accounts
        {
            $this->id = (int)$value;

            return $this;
        }

        public function getId(): int
        {
            return $this->id;
        }

        public function setNumber($value): Accounts
        {
            $this->number = (float)$value;

            return $this;
        }

        public function getNumber(): float
        {
            return $this->number;
        }

        public function setStatus($value): Accounts
        {
            $this->status = (string)$value;

            return $this;
        }

        public function getStatus(): string
        {
            return $this->status;
        }

        public function setDate($value): Accounts
        {
            $this->date = (string)$value;

            return $this;
        }

        public function getDate(): string
        {
            return $this->date;
        }

        public function setDiscount($value): Accounts
        {
            $this->discount = (float)$value;

            return $this;
        }

        public function getDiscount(): float
        {
            return $this->discount;
        }

        public function setAccountComposition(array $value): Accounts
        {
            $this->account_composition = json_encode($value, JSON_UNESCAPED_UNICODE);

            return $this;
        }

        public function getAccountComposition(): array
        {
            return json_decode($this->account_composition ?? '{}', false);
        }

        /**
         * @inheritDoc
         */
        protected function getObjectVars(): array
        {
            return get_object_vars($this);
        }

    }
}