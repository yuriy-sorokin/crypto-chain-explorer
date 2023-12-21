<?php
declare(strict_types=1);

namespace App\Integration\CryptoAssetStatistics\Vendor\BlockCypher;

use App\Domain\CryptoAssetRequest\Model\CryptoAssetRequest;
use App\Domain\CryptoAssetRequest\Model\CryptoAssetResponse;
use App\Integration\CryptoAssetStatistics\Repository\CryptoAssetStatisticsRepositoryInterface;
use App\Integration\CryptoAssetStatistics\Vendor\BlockCypher\DateFilterFactory\DateFilterFactory;
use App\Integration\CryptoAssetStatistics\Vendor\BlockCypher\DateFilterFactory\DateFilterFactoryMessage;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class BlockCypherRepository implements CryptoAssetStatisticsRepositoryInterface
{
    public function __construct(private readonly HttpClientInterface $client, private readonly DateFilterFactory $dateFilterFactory) {}

    public function find(CryptoAssetRequest $request): CryptoAssetResponse
    {
        $response = $this->client->request(
            'GET',
            \sprintf('https://api.blockcypher.com/v1/%s/main', $request->getCryptoAsset()->getAbbreviation())
        );
        $decodedResponse = \json_decode($response->getContent(), true);

        $dateTransformerMessage = new DateFilterFactoryMessage(
            $request->getCryptoAsset(),
            (int) $decodedResponse['height'],
            new \DateTimeImmutable(),
            $request->getDateFrom(),
            $request->getDateTo()
        );
        $dateFilter = $this->dateFilterFactory->create($dateTransformerMessage);

        $response = $this->client->request(
            'GET',
            \sprintf('https://api.blockcypher.com/v1/%s/main/addrs/%s', $request->getCryptoAsset()->getAbbreviation(), $request->getAddress()),
            [
                'query' => [
                    'before' => \max(0, $dateTransformerMessage->getCurrentHeight() - $dateFilter->getBlocksFrom()),
                    'after' => \max(0, $dateTransformerMessage->getCurrentHeight() - $dateFilter->getBlocksTo()),
                ],
            ]
        );
        
        $decodedResponse = \json_decode($response->getContent(), true);

        $transactionsCount = 0;
        $transactionsTotalValue = 0;

        if (false === array_key_exists('txrefs', $decodedResponse)) {
            return new CryptoAssetResponse($request);
        }

        foreach ($decodedResponse['txrefs'] as $transaction) {
            $transactionValue = (int) $transaction['value'];

            if ($request->getThreshold() <= $transactionValue) {
                $transactionsCount++;
                $transactionsTotalValue += $transactionValue;
            }
        }

        if (0 === $transactionsCount) {
            return new CryptoAssetResponse($request);
        }

        return new CryptoAssetResponse(
            $request,
            $transactionsCount,
            (int) \floor($transactionsTotalValue / $transactionsCount)
        );
    }
}
