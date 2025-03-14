<?php

namespace App\DataFixtures;

use App\Entity\Cryptocurrency;
use App\Entity\FiatCurrency;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CryptoFixtures extends Fixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $cryptocurrencies = [
            ['BTC', 'Bitcoin'],
            ['ETH', 'Ethereum'],
            ['LTC', 'Litecoin'],
            ['DOGE', 'Dogecoin'],
            ['XRP', 'Ripple'],
        ];

        $fiatCurrencies = [
            ['USD', 'US Dollar'],
            ['EUR', 'Euro'],
            ['UAH', 'Ukrainian Hryvnia'],
            ['GBP', 'British Pound'],
            ['JPY', 'Japanese Yen'],
        ];

        foreach ($cryptocurrencies as [$symbol, $name]) {
            $crypto = new Cryptocurrency(null, $symbol, $name);
            $manager->persist($crypto);
        }

        foreach ($fiatCurrencies as [$symbol, $name]) {
            $fiat = new FiatCurrency(null, $symbol, $name);
            $manager->persist($fiat);
        }

        $manager->flush();
    }
}
