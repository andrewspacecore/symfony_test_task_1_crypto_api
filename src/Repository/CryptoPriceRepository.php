<?php

namespace App\Repository;

use App\Entity\CryptoPrice;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CryptoPrice>
 */
class CryptoPriceRepository extends ServiceEntityRepository
{

    /**
     * @param ManagerRegistry $registry
     * @param EntityManagerInterface $entityManager
     * @param FiatCurrencyRepository $fiatCurrencyRepository
     * @param CryptocurrencyRepository $cryptocurrencyRepository
     */
    public function __construct(
        protected ManagerRegistry          $registry,
        protected EntityManagerInterface   $entityManager,
        protected FiatCurrencyRepository   $fiatCurrencyRepository,
        protected CryptocurrencyRepository $cryptocurrencyRepository
    )
    {
        parent::__construct($registry, CryptoPrice::class);
    }

    /**
     * @param string $cryptoCode
     * @param array $priceData
     */
    public function createCryptoPrice(string $cryptoCode, array $priceData): void
    {
        foreach ($priceData as $currencyCode => $price) {
            $fiatCurrency = $this->fiatCurrencyRepository->findOneBy(['code' => $currencyCode]);
            $cryptocurrency = $this->cryptocurrencyRepository->findOneBy(['code' => $cryptoCode]);

            if (!$fiatCurrency || !$cryptocurrency) {
                continue;
            }

            $cryptoPrice = new CryptoPrice(
                null,
                $cryptocurrency,
                $fiatCurrency,
                $price,
                new \DateTime(),
            );

            $this->entityManager->persist($cryptoPrice);
        }

        $this->entityManager->flush();
    }

    /**
     * @param string $cryptoCode
     * @param string $fiatCode
     * @param array $filters
     * @return array|null
     */
    public function findPriceByCryptoCodeAndFiatCode(string $cryptoCode, string $fiatCode, array $filters = []): ?array
    {
        $sort = $filters['sort'] ?? null;
        if (!$sort) {
            return $this->findLastPriceByCryptoCodeAndFiatCode($cryptoCode, $fiatCode);
        }
        $sortValue = match ($sort) {
            'hour' => '-1 hour',
            'twohour' => '-2 hour',
            'day' => '-24 hour',
            'week' => '-7 day',
            'month' => '-30 day',
        };

        $sortValueTime = new \DateTime();
        $sortValueTime->modify($sortValue);

        $sql = $this->createQueryBuilder('cp')
            ->select('cp.price', 'cp.recordedAt')
            ->innerJoin('cp.cryptocurrency', 'c')
            ->innerJoin('cp.fiatCurrency', 'f')
            ->where('c.code = :cryptoCode')
            ->andWhere('f.code = :fiatCode')
            ->andWhere('cp.recordedAt >= :timeAgo')
            ->setParameter('cryptoCode', $cryptoCode)
            ->setParameter('fiatCode', $fiatCode)
            ->setParameter('timeAgo', $sortValueTime)
            ->orderBy('cp.recordedAt', 'DESC');

        $queryResult = $sql->getQuery()->getArrayResult();

        foreach ($queryResult as &$result) {
            $result['recordedAt'] = $result['recordedAt']->format('Y-m-d H:i:s');
            $result['price'] = (float)$result['price'];
        }
        return $queryResult;
    }

    /**
     * @param string $cryptoCode
     * @param string $fiatCode
     * @return array|null
     */
    public function findLastPriceByCryptoCodeAndFiatCode(string $cryptoCode, string $fiatCode): array|null
    {
        return $this->createQueryBuilder('cp')
            ->select('cp.price')
            ->innerJoin('cp.cryptocurrency', 'c')
            ->innerJoin('cp.fiatCurrency', 'f')
            ->where('c.code = :cryptoCode')
            ->andWhere('f.code = :fiatCode')
            ->setParameter('cryptoCode', $cryptoCode)
            ->setParameter('fiatCode', $fiatCode)
            ->orderBy('cp.recordedAt', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }
}
