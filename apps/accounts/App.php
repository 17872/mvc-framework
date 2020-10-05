<?php

declare(strict_types=1);

namespace App\accounts {

    use Vendor\kernel\AppLoader;

    class App extends AppLoader
    {
        /**
         * @inheritDoc
         */
        public function getControllers(): array
        {
            return [
                'get-accounts' => \App\accounts\controllers\Main::class,
            ];
        }
    }
}