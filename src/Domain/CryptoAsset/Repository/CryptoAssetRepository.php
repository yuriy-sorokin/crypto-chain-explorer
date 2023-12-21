<?php
declare(strict_types=1);

namespace App\Domain\CryptoAsset\Repository;

use App\Domain\CryptoAsset\Model\CryptoAsset;
use Doctrine\ORM\EntityManagerInterface;

class CryptoAssetRepository
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {}

    public function find(string $abbreviation): ?CryptoAsset
    {
        return $this->entityManager->find(CryptoAsset::class, $abbreviation);
    }
}
