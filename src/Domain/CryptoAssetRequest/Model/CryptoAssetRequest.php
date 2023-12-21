<?php
declare(strict_types=1);

namespace App\Domain\CryptoAssetRequest\Model;

use App\Domain\CryptoAsset\Model\CryptoAsset;

class CryptoAssetRequest
{
    private ?int $id;

    public function __construct(
        private readonly CryptoAsset $cryptoAsset,
        private readonly string $address,
        private readonly \DateTimeInterface $dateFrom,
        private readonly \DateTimeInterface $dateTo,
        private readonly int $threshold,
        private readonly string $uniqueContext
    ) {}

    public function getCryptoAsset(): CryptoAsset
    {
        return $this->cryptoAsset;
    }

    public function getAddress(): string
    {
        return $this->address;
    }

    public function getDateFrom(): \DateTimeInterface
    {
        return $this->dateFrom;
    }

    public function getDateTo(): \DateTimeInterface
    {
        return $this->dateTo;
    }

    public function getThreshold(): int
    {
        return $this->threshold;
    }
}
