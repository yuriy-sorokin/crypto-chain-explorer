<?php
declare(strict_types=1);

namespace App\Domain\CryptoAssetRequest\Model;

class CryptoAssetResponse
{
    private ?int $id;

    public function __construct(private readonly CryptoAssetRequest $request, private readonly int $count = 0, private readonly int $averageQuantity = 0) {}

    public function getRequest(): CryptoAssetRequest
    {
        return $this->request;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function getAverageQuantity(): int
    {
        return $this->averageQuantity;
    }
}
