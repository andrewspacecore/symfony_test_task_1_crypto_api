<?php declare(strict_types=1);

namespace App\Controller;

use App\Enum\SortCryptoEnum;
use App\Repository\CryptocurrencyRepository;
use App\Repository\CryptoPriceRepository;
use App\Repository\FiatCurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CryptoController extends AbstractController
{
    /**
     * @param CryptoPriceRepository $cryptoPriceRepository
     * @param CryptocurrencyRepository $cryptocurrencyRepository
     * @param FiatCurrencyRepository $fiatCurrencyRepository
     * @param ValidatorInterface $validator
     */
    public function __construct(
        protected CryptoPriceRepository    $cryptoPriceRepository,
        protected CryptocurrencyRepository $cryptocurrencyRepository,
        protected FiatCurrencyRepository   $fiatCurrencyRepository,
        protected ValidatorInterface       $validator
    )
    {
    }

    /**
     * @param string $cryptoCode
     * @param string $fiatCode
     * @param string|null $sort
     * @return JsonResponse
     */
    #[Route('/api/price/{cryptoCode}/{fiatCode}/{sort?}', name: 'api_crypto_price', methods: ['GET'])]
    public function cryptoPriceAction(string $cryptoCode, string $fiatCode, ?string $sort = null): JsonResponse
    {
        $filters = [
            'sort' => $sort
        ];
        $validSortValues = array_map(fn($case) => $case->value, SortCryptoEnum::cases());

        $cryptoCodeEntity = $this->cryptocurrencyRepository->findOneBy(['code' => strtoupper($cryptoCode)]);
        $fiatCodeEntity = $this->fiatCurrencyRepository->findOneBy(['code' => strtoupper($fiatCode)]);

        if (!$cryptoCodeEntity || !$fiatCodeEntity || ($sort && !in_array($sort, $validSortValues))) {
            return $this->json([
                'error' => 'wrong params'
            ], JsonResponse::HTTP_BAD_REQUEST);
        }
        $cryptoPrice = $this->cryptoPriceRepository->findPriceByCryptoCodeAndFiatCode(
            $cryptoCodeEntity->getCode(), $fiatCodeEntity->getCode(), $filters
        );

        return !$sort
            ? $this->json([
                'price' => (float)$cryptoPrice['price']
            ], JsonResponse::HTTP_OK)
            : $this->json($cryptoPrice, JsonResponse::HTTP_OK);
    }
}