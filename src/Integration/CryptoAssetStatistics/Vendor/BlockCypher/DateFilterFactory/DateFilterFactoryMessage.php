<?php
declare(strict_types=1);

namespace App\Integration\CryptoAssetStatistics\Vendor\BlockCypher\DateFilterFactory;

use App\Domain\CryptoAsset\Model\CryptoAsset;

class DateFilterFactoryMessage
{
    public function __construct(
        private readonly CryptoAsset $cryptoAsset,
        private readonly int $currentHeight,
        private readonly \DateTimeInterface $currentTime,
        private readonly \DateTimeInterface $dateFrom,
        private readonly \DateTimeInterface $dateTo
    ) {}

    public function getCryptoAsset(): CryptoAsset
    {
        return $this->cryptoAsset;
    }

    public function getCurrentHeight(): int
    {
        return $this->currentHeight;
    }

    public function getCurrentTime(): \DateTimeInterface
    {
        return $this->currentTime;
    }

    public function getDateFrom(): \DateTimeInterface
    {
        return $this->dateFrom;
    }

    public function getDateTo(): \DateTimeInterface
    {
        return $this->dateTo;
    }
}
