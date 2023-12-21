<?php
declare(strict_types=1);

namespace App\Integration\CryptoAssetStatistics\Repository;

use App\Domain\CryptoAsset\Model\CryptoAsset;

class CryptoAssetStatisticsRepositoryQuery
{
    public function __construct(
        private readonly CryptoAsset $asset,
        private readonly string $address,
        private readonly string $fromDate,
        private readonly string $toDate,
        private readonly int $threshold
    ) {
    }

    public function getAsset(): CryptoAsset
    {
        return $this->asset;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getFromDate(): string
    {
        return $this->fromDate;
    }

    public function getToDate(): string
    {
        return $this->toDate;
    }

    public function getThreshold(): int
    {
        return $this->threshold;
    }
}
