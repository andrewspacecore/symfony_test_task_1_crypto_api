<?php declare(strict_types=1);

namespace App\Service;

use App\Interface\ApiClientInterface;
use Exception;
use Psr\Log\LoggerInterface;

class CryptoCompareService
{
    protected string $apiKey;

    /**
     * @param string $apiKey
     * @param ApiClientInterface $client
     * @param LoggerInterface $logger
     */
    public function __construct(string $apiKey, protected ApiClientInterface $client, protected LoggerInterface $logger)
    {
        $this->apiKey = $apiKey;
    }

    /**
     * @param string $fromCurrency
     * @param string $toCurrency
     * @return array
     * @throws Exception
     */
    public function getCryptoPriceMulti(string $fromCurrency, string $toCurrency): array
    {
        $url = "https://min-api.cryptocompare.com/data/pricemulti";
        try {
            $response = $this->client->get($url, [
                'query' => [
                    'fsyms' => $fromCurrency,
                    'tsyms' => $toCurrency,
                    'api_key' => $this->apiKey
                ]
            ]);

            return json_decode($response->getBody()->getContents(), true);

        } catch (Exception $e) {
            $this->logger->error('Error occurred while making request to CryptoCompare API', [
                'error_message' => $e->getMessage(),
                'url' => $url,
                'fsym' => $fromCurrency,
                'tsyms' => $toCurrency
            ]);
            throw new Exception('Error occurred while making request to CryptoCompare API');
        }
    }
}