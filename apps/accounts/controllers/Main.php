<?php

declare(strict_types=1);

namespace App\accounts\controllers {

    use Vendor\kernel\Kernel;
    use App\accounts\entities\Accounts;

    class Main
    {
        /**
         * Displays account data
         */
        public function actionAll()
        {
            echo '<pre>' . PHP_EOL;
            $account = Accounts::find()->where(
                [
                    [
                        'AND' => [
                            ['date', '>=', '2020-01-01'],
                        ]
                    ],
                ]
            )->one();
            var_dump($account);

            $accounts = Accounts::find()->all();
            var_dump($accounts);
            $update = $accounts[0] = $accounts[0]->setNumber('10004')->save();
            var_dump($update);

            $newAccount = new Accounts();
            $insert = $newAccount
                ->setNumber('10005')
                ->setStatus('PAID')
                ->setDiscount(20)
                ->setAccountComposition(['id' => 5, 'count' => 1, 'name' => 'account 5'])->save();
            var_dump($insert);
            echo '</pre>' . PHP_EOL;
        }
    }
}