<?php
declare(strict_types=1);

namespace App\Integration\CryptoAssetStatistics\Vendor\BlockCypher\DateFilterFactory;

class DateFilter
{
    public function __construct(private readonly int $blocksFrom, private readonly int $blocksTo) {}

    public function getBlocksFrom(): int
    {
        return $this->blocksFrom;
    }

    public function getBlocksTo(): int
    {
        return $this->blocksTo;
    }
}
