<?php
declare(strict_types=1);

namespace App\Tests\Domain\CryptoAssetRequest\API\Query\GetCryptoAssetStatistics;

use App\Domain\CryptoAssetRequest\API\Query\GetCryptoAssetStatistics\GetCryptoAssetStatistics;
use App\Domain\CryptoAssetRequest\Model\CryptoAssetRequest;
use App\Framework\Decorator\MessageBus\MessageBus;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class GetCryptoAssetStatisticsHandlerTest extends KernelTestCase
{
    private const string ADDRESS = '1DEP8i3QJCsomS4BSMY2RpU1upv62aGvhD';

    public static function getTestCases(): array
    {
        return [
            'should put a new request into a database and then use it for the following request' => [
                [
                    'asset' => 'btc',
                    'responses' => fn($method, $url) => match([$method, $url]) {
                        [
                            'GET',
                            'https://api.blockcypher.com/v1/btc/main'
                        ] => 'response-1.json',
                        [
                            'GET',
                            \sprintf('https://api.blockcypher.com/v1/btc/main/addrs/%s', static::ADDRESS)
                        ] => 'response-2.json',
                    },
                    'expectedRequestsInDatabase' => 1,
                ],
            ],
        ];
    }

    /**
     * @param array $testCase
     * @dataProvider getTestCases
     */
    public function test(array $testCase): void
    {
        $test = $this;

        $httpClient = $this->createMock(HttpClientInterface::class);
        $httpClient
            ->expects(static::any())
            ->method('request')
            ->willReturnCallback(
                static function (string $method, string $url) use($test, $testCase) {
                    $responseMock = $test->createMock(ResponseInterface::class);
                    $responseMock
                        ->expects(static::any())
                        ->method('getContent')
                        ->willReturn(
                            \file_get_contents(
                                \sprintf(
                                    '%s%sfixtures%s%s',
                                    __DIR__,
                                    \DIRECTORY_SEPARATOR,
                                    \DIRECTORY_SEPARATOR,
                                    $testCase['responses']($method, $url)
                                )
                            )
                        );

                    return $responseMock;
                }
            );

        static::getContainer()->set(HttpClientInterface::class, $httpClient);

        $message = new GetCryptoAssetStatistics(
            $testCase['asset'],
            static::ADDRESS,
            '2015-12-01',
            '2015-12-15',
            20
        );

        /** @var MessageBus $messageBus */
        $messageBus = static::getContainer()->get(MessageBus::class);
        $messageBus->dispatch($message);
        $messageBus->dispatch($message);

        /** @var EntityManager $entityManager */
        $entityManager = static::getContainer()->get(EntityManagerInterface::class);

        $actualRequestsInDatabase = $entityManager
            ->createQueryBuilder()
            ->select('count(c.id)')
            ->from(CryptoAssetRequest::class, 'c')
            ->getQuery()
            ->getSingleScalarResult();

        static::assertSame($testCase['expectedRequestsInDatabase'], $actualRequestsInDatabase);
    }
}
