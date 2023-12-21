<?php
declare(strict_types=1);

namespace App\Domain\CryptoAssetRequest\API\Query\GetCryptoAssetStatistics;

class GetCryptoAssetStatistics
{
    public function __construct(
        private readonly string $asset,
        private readonly string $address,
        private readonly string $fromDate,
        private readonly string $toDate,
        private readonly int $threshold
    ) {
    }

    public function getAsset(): string
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
