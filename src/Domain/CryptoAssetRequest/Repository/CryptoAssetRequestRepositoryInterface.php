<?php
declare(strict_types=1);

namespace App\Domain\CryptoAssetRequest\Repository;

use App\Domain\CryptoAssetRequest\Model\CryptoAssetRequest;

interface CryptoAssetRequestRepositoryInterface
{
    public function save(CryptoAssetRequest $request): void;
}
