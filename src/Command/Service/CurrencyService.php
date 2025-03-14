<?php declare(strict_types=1);

namespace App\Command\Service;

use App\Repository\CryptocurrencyRepository;
use App\Repository\FiatCurrencyRepository;

class CurrencyService
{
    /**
     * @param CryptocurrencyRepository $cryptocurrencyRepository
     * @param FiatCurrencyRepository $fiatCurrencyRepository
     */
    public function __construct(
        protected CryptocurrencyRepository $cryptocurrencyRepository,
        protected FiatCurrencyRepository   $fiatCurrencyRepository,
    )
    {
    }

    /**
     * @return array
     */
    public function getCurrencies(): array
    {
        $cryptocurrencies = array_map(function ($item) {
            return $item->getCode();
        }, $this->cryptocurrencyRepository->findAll());

        $fiats = implode(',', array_map(function ($item) {
            return $item->getCode();
        }, $this->fiatCurrencyRepository->findAll()));

        return [
            'cryptocurrencies' => $cryptocurrencies,
            'fiats' => $fiats,
        ];
    }

    /**
     * @return bool
     */
    public function areCurrenciesValid(): bool
    {
        $currencies = $this->getCurrencies();
        return !empty($currencies['cryptocurrencies']) || !empty($currencies['fiats']);
    }
}