<?php

namespace App\Entity;

use App\Repository\CryptoPriceRepository;
use DateTimeInterface;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CryptoPriceRepository::class)]
class CryptoPrice
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(name: 'id', type: 'integer')]
    protected ?int $id;

    #[ORM\ManyToOne(targetEntity: Cryptocurrency::class, cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'cryptocurrency_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    protected Cryptocurrency $cryptocurrency;

    #[ORM\ManyToOne(targetEntity: 'App\Entity\FiatCurrency', cascade: ['persist'])]
    #[ORM\JoinColumn(name: 'fiat_currency_id', referencedColumnName: 'id', onDelete: 'CASCADE',)]
    protected FiatCurrency $fiatCurrency;

    #[ORM\Column(name: 'price', type: 'decimal', precision: 30, scale: 10)]
    protected float $price;

    #[ORM\Column(name: 'recorded_at', type: 'datetime')]
    protected DateTimeInterface $recordedAt;

    /**
     * @param int|null $id
     * @param Cryptocurrency|null $cryptocurrency
     * @param FiatCurrency|null $fiatCurrency
     * @param float|null $price
     * @param DateTimeInterface|null $recordedAt
     */
    public function __construct(
        int               $id = null,
        Cryptocurrency    $cryptocurrency = null,
        FiatCurrency      $fiatCurrency = null,
        float             $price = null,
        DateTimeInterface $recordedAt = null,
    )
    {
        $this->id = $id;
        $this->cryptocurrency = $cryptocurrency;
        $this->fiatCurrency = $fiatCurrency;
        $this->price = $price;
        $this->recordedAt = $recordedAt;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCryptocurrency(): Cryptocurrency
    {
        return $this->cryptocurrency;
    }

    public function setCryptocurrency(Cryptocurrency $cryptocurrency): void
    {
        $this->cryptocurrency = $cryptocurrency;
    }

    public function getFiatCurrency(): FiatCurrency
    {
        return $this->fiatCurrency;
    }

    public function setFiatCurrency(FiatCurrency $fiatCurrency): void
    {
        $this->fiatCurrency = $fiatCurrency;
    }

    public function getPrice(): float
    {
        return $this->price;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function getRecordedAt(): DateTimeInterface
    {
        return $this->recordedAt;
    }

    public function setRecordedAt(?DateTimeInterface $recordedAt): void
    {
        $this->recordedAt = $recordedAt ?? new \DateTime();
    }
}
