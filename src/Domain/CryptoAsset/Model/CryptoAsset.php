<?php
declare(strict_types=1);

namespace App\Domain\CryptoAsset\Model;

class CryptoAsset
{
    public function __construct(private readonly string $abbreviation) {}

    public function getAbbreviation(): string
    {
        return $this->abbreviation;
    }
}
