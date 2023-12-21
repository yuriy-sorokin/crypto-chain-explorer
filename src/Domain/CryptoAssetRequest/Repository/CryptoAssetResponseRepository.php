<?php
declare(strict_types=1);

namespace App\Domain\CryptoAssetRequest\Repository;

use App\Domain\CryptoAssetRequest\Model\CryptoAssetRequest;
use App\Domain\CryptoAssetRequest\Model\CryptoAssetResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CryptoAssetResponseRepository extends ServiceEntityRepository implements CryptoAssetResponseRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CryptoAssetResponse::class);
    }

    public function findOneByRequest(CryptoAssetRequest $request): ?CryptoAssetResponse
    {
        return $this->findOneBy(['request' => $request]);
    }

    public function save(CryptoAssetResponse $response): void
    {
        $this->getEntityManager()->persist($response);
        $this->getEntityManager()->flush();
    }
}
