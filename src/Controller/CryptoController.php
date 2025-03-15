<?php declare(strict_types=1);

namespace App\Controller;

use App\Repository\CryptocurrencyRepository;
use App\Repository\CryptoPriceRepository;
use App\Repository\FiatCurrencyRepository;
use App\Request\Crypto\CryptoPriceRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\ConstraintViolationInterface;
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
     * @param Request $request
     * @return JsonResponse
     */
    #[Route('/api/crypto/price/{cryptoCode}/{fiatCode}/{sort?}', name: 'api_crypto_price', methods: ['GET'])]
    public function cryptoPriceAction(Request $request): JsonResponse
    {
        $cryptoCode = strtoupper($request->get('cryptoCode'));
        $fiatCode = strtoupper($request->get('fiatCode'));
        $sort = $request->get('sort');
        $vRequest = new CryptoPriceRequest($cryptoCode, $fiatCode, $sort);

        $errors = $this->validator->validate($vRequest);

        if (count($errors)) {
            $errorMessages = [];
            /** @var ConstraintViolationInterface $error */
            foreach ($errors as $error) {
                $errorMessages[$error->getPropertyPath()] = $error->getMessage();
            }
            return $this->json($errorMessages, JsonResponse::HTTP_BAD_REQUEST);
        }

        $filters = [
            'sort' => $sort
        ];

        $cryptoPrice = $this->cryptoPriceRepository->findPriceByCryptoCodeAndFiatCode(
            $cryptoCode, $fiatCode, $filters
        );

        return !$sort
            ? $this->json([
                'price' => (float)$cryptoPrice['price']
            ], JsonResponse::HTTP_OK)
            : $this->json($cryptoPrice, JsonResponse::HTTP_OK);
    }
}