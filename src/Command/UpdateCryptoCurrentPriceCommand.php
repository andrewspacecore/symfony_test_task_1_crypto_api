<?php

namespace App\Command;

use App\Command\Service\CurrencyService;
use App\Repository\CryptoPriceRepository;
use App\Service\CryptoCompareService;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:crypto-current-price',
    description: 'crypto current price'
)]
class UpdateCryptoCurrentPriceCommand extends Command
{

    /**
     * @param CryptoCompareService $cryptoCompareService
     * @param CryptoPriceRepository $cryptoPriceRepository
     * @param CurrencyService $currencyService
     * @param LoggerInterface $cryptoLogger
     */
    public function __construct(
        protected CryptoCompareService  $cryptoCompareService,
        protected CryptoPriceRepository $cryptoPriceRepository,
        protected CurrencyService       $currencyService,
        protected LoggerInterface       $cryptoLogger,
    )
    {
        parent::__construct();

    }

    /**
     * @throws \Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        if (!$this->currencyService->areCurrenciesValid()) {
            return Command::SUCCESS;
        }

        $cryptocurrencies = $this->currencyService->getCurrencies();
        $cryptoSymbols = implode(',', $cryptocurrencies['cryptocurrencies']);
        $fiats = $cryptocurrencies['fiats'];

        $pricesData = $this->cryptoCompareService->getCryptoPriceMulti($cryptoSymbols, $fiats);

        foreach ($this->generateCryptoPrices($cryptocurrencies['cryptocurrencies'], $pricesData) as $cryptocurrency => $price) {
            $this->cryptoPriceRepository->createCryptoPrice($cryptocurrency, $price);
        }

        $this->cryptoLogger->info('Crypto current price updated', ['data' => $pricesData]);

        $io = new SymfonyStyle($input, $output);
        $io->success('Crypto current price updated!!!');

        return Command::SUCCESS;
    }

    /**
     * @param array $cryptocurrencies
     * @param array $pricesData
     * @return iterable
     */
    private function generateCryptoPrices(array $cryptocurrencies, array $pricesData): iterable
    {
        foreach ($cryptocurrencies as $cryptocurrency) {
            if (isset($pricesData[$cryptocurrency])) {
                yield $cryptocurrency => $pricesData[$cryptocurrency];
            }
        }
    }
}
