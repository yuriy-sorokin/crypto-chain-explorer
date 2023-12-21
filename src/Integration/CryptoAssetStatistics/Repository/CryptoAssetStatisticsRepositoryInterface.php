<?php
declare(strict_types=1);

namespace App\Integration\CryptoAssetStatistics\Repository;

use App\Domain\CryptoAssetRequest\Model\CryptoAssetRequest;
use App\Domain\CryptoAssetRequest\Model\CryptoAssetResponse;

interface CryptoAssetStatisticsRepositoryInterface
{
    public function find(CryptoAssetRequest $request): CryptoAssetResponse;
}
