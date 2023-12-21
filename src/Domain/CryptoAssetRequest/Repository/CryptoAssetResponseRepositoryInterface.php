<?php
declare(strict_types=1);

namespace App\Domain\CryptoAssetRequest\Repository;

use App\Domain\CryptoAssetRequest\Model\CryptoAssetRequest;
use App\Domain\CryptoAssetRequest\Model\CryptoAssetResponse;

interface CryptoAssetResponseRepositoryInterface
{
    public function findOneByRequest(CryptoAssetRequest $request): ?CryptoAssetResponse;

    public function save(CryptoAssetResponse $response): void;
}
