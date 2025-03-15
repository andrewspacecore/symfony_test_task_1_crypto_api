<?php declare(strict_types=1);

namespace App\Request\Crypto;

use App\Entity\Cryptocurrency;
use App\Entity\FiatCurrency;
use App\Enum\SortCryptoPriceEnum;
use App\Request\Validator\Constraint\EntityExists;
use Symfony\Component\Validator\Constraints as Assert;

class CryptoPriceRequest
{
    #[Assert\NotBlank(message: 'Crypto code is required')]
    #[Assert\Regex('/^\w+$/', message: 'Crypto code is not valid')]
    #[EntityExists(entityClass: CryptoCurrency::class, field: 'code', message: 'Crypto code does not exist')]
    public string $cryptoCode;

    #[Assert\NotBlank(message: 'Fiat code is required')]
    #[Assert\Regex('/^\w+$/', message: 'Fiat code is not valid')]
    #[EntityExists(entityClass: FiatCurrency::class, field: 'code', message: 'Fiat code does not exist')]
    public string $fiatCode;

    #[Assert\Choice(callback: [SortCryptoPriceEnum::class, 'values'], message: 'Invalid sort')]
    public ?string $sort = null;

    /**
     * @param string $cryptoCode
     * @param string $fiatCode
     * @param string|null $sort
     */
    public function __construct(
        string $cryptoCode,
        string $fiatCode,
        string $sort = null
    )
    {
        $this->cryptoCode = $cryptoCode;
        $this->fiatCode = $fiatCode;
        $this->sort = $sort;
    }
}