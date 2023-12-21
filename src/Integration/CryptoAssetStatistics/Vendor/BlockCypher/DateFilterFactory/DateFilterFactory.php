<?php
declare(strict_types=1);

namespace App\Integration\CryptoAssetStatistics\Vendor\BlockCypher\DateFilterFactory;

class DateFilterFactory
{
    private const array NEW_BLOCK_INTERVAL_IN_SECONDS = [
        'btc' => 600,
        'eth' => 12,
    ];

    public function create(DateFilterFactoryMessage $message): DateFilter
    {
        $currentTimestamp = $message->getCurrentTime()->format('U');
        $dateFromTimeDifferenceInSeconds = $currentTimestamp - $message->getDateFrom()->format('U');
        $dateToTimeDifferenceInSeconds = $currentTimestamp - $message->getDateTo()->format('U');
        $newBlockInterval = static::NEW_BLOCK_INTERVAL_IN_SECONDS[$message->getCryptoAsset()->getAbbreviation()];

        return new DateFilter(
            (int) \floor($dateToTimeDifferenceInSeconds / $newBlockInterval),
            (int) \floor($dateFromTimeDifferenceInSeconds / $newBlockInterval)
        );
    }
}
