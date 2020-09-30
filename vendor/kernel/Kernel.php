<?php


namespace Vendor\kernel;

use Vendor\db\Db;

/**
 * Ядро системы
 *
 * @package Vendor\kernel
 */
class Kernel
{
    /**
     * @var Db объект для работы с базой данных
     */
    public $db;

    public function __construct()
    {
        $this->db = new Db();
    }
}