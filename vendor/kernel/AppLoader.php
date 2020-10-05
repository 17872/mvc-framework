<?php

declare(strict_types=1);

namespace Vendor\kernel {

    /**
     * Загрузчик приложений системы
     *
     * @package Vendor\kernel
     */
    abstract class AppLoader
    {
        /**
         * Получает классы контроллеров приложения
         *
         * @return array
         */
        abstract public function getControllers(): array;
    }
}