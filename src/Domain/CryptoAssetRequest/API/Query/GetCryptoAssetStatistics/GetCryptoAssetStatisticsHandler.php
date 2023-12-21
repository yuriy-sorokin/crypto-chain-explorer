<?php
declare(strict_types=1);

namespace App\Domain\CryptoAssetRequest\API\Query\GetCryptoAssetStatistics;

use App\Domain\CryptoAsset\Model\CryptoAsset;
use App\Domain\CryptoAsset\Repository\CryptoAssetRepository;
use App\Domain\CryptoAssetRequest\Model\CryptoAssetRequest;
use App\Domain\CryptoAssetRequest\Model\CryptoAssetResponse;
use App\Domain\CryptoAssetRequest\Repository\CryptoAssetRequestRepositoryInterface;
use App\Domain\CryptoAssetRequest\Repository\CryptoAssetResponseRepositoryInterface;
use App\Framework\Decorator\Lock\Lock;
use App\Integration\CryptoAssetStatistics\Repository\CryptoAssetStatisticsRepositoryInterface;

class GetCryptoAssetStatisticsHandler
{
    public function __construct(
        private readonly Lock $lock,
        private readonly CryptoAssetRepository $cryptoAssetRepository,
        private readonly CryptoAssetRequestRepositoryInterface $cryptoAssetRequestRepository,
        private readonly CryptoAssetResponseRepositoryInterface $cryptoAssetResponseRepository,
        private readonly CryptoAssetStatisticsRepositoryInterface $cryptoAssetStatisticsRepository
    ) {}

    public function __invoke(GetCryptoAssetStatistics $query): CryptoAssetResponse
    {
        $uniqueContext = $this->getUniqueContext($query);

        $this->lock->acquire('lock');

        $existingRequest = $this->cryptoAssetRequestRepository->findOneBy(['uniqueContext' => $uniqueContext]);

        if (true === $existingRequest instanceof CryptoAssetRequest) {
            $this->lock->release();

            return $this->cryptoAssetResponseRepository->findOneByRequest($existingRequest);
        }

        $cryptoAsset = $this->cryptoAssetRepository->find($query->getAsset());

        if (false === $cryptoAsset instanceof CryptoAsset) {
            throw new \UnexpectedValueException('Unknown asset');
        }

        $response = $this->cryptoAssetStatisticsRepository->find(
            new CryptoAssetRequest(
                $cryptoAsset,
                $query->getAddress(),
                new \DateTimeImmutable($query->getFromDate()),
                new \DateTimeImmutable($query->getToDate()),
                $query->getThreshold(),
                $uniqueContext
            )
        );

        $this->cryptoAssetRequestRepository->save($response->getRequest());
        $this->cryptoAssetResponseRepository->save($response);

        $this->lock->release();

        return $response;
    }

    private function getUniqueContext(GetCryptoAssetStatistics $query): string
    {
        return \sha1(
            \json_encode(
                [
                    'asset' => $query->getAsset(),
                    'address' => $query->getAddress(),
                    'fromDate' => $query->getFromDate(),
                    'toDate' => $query->getToDate(),
                    'threshold' => $query->getThreshold(),
                ]
            )
        );
    }
}
