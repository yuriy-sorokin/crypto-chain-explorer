<?php
declare(strict_types=1);

namespace App\Domain\CryptoAssetRequest\Repository;

use App\Domain\CryptoAssetRequest\Model\CryptoAssetRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class CryptoAssetRequestRepository extends ServiceEntityRepository implements CryptoAssetRequestRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CryptoAssetRequest::class);
    }

    public function save(CryptoAssetRequest $request): void
    {
        $this->getEntityManager()->persist($request);
        $this->getEntityManager()->flush();
    }
}
