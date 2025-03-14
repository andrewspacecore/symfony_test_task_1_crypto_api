<?php

namespace App\Entity;

use App\Repository\CryptocurrencyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CryptocurrencyRepository::class)]
class Cryptocurrency
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(name: 'id', type: 'integer')]
    protected ?int $id = null;

    #[ORM\Column(name: 'code', type: 'string', length: 5, unique: true)]
    protected string $code;

    #[ORM\Column(name: 'name', type: 'string', length: 100)]
    protected string $name;

    /**
     * @param int|null $id
     * @param string|null $code
     * @param string|null $name
     */
    public function __construct(
        ?int   $id = null,
        string $code = null,
        string $name = null
    )
    {
        $this->id = $id;
        $this->code = $code;
        $this->name = $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCode(): string
    {
        return $this->code;
    }

    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
