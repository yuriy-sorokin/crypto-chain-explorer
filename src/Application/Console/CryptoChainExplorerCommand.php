<?php
declare(strict_types=1);

namespace App\Application\Console;

use App\Domain\CryptoAssetRequest\API\Query\GetCryptoAssetStatistics\GetCryptoAssetStatistics;
use App\Domain\CryptoAssetRequest\Model\CryptoAssetResponse;
use App\Framework\Decorator\MessageBus\MessageBus;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Helper\TableStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class CryptoChainExplorerCommand extends Command
{
    private readonly MessageBus $messageBus;

    public function __construct(MessageBus $messageBus)
    {
        parent::__construct();

        $this->messageBus = $messageBus;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:get-crypto-asset')
            ->addOption('asset', 'a', InputOption::VALUE_REQUIRED)
            ->addOption('address', 'd', InputOption::VALUE_REQUIRED)
            ->addOption('from-date', 'f', InputOption::VALUE_REQUIRED)
            ->addOption('to-date', 't', InputOption::VALUE_REQUIRED)
            ->addOption('threshold', 'r', InputOption::VALUE_REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        /** @var CryptoAssetResponse $cryptoAssetResponse */
        $cryptoAssetResponse = $this->messageBus->dispatch(
            new GetCryptoAssetStatistics(
                $input->getOption('asset'),
                $input->getOption('address'),
                $input->getOption('from-date'),
                $input->getOption('to-date'),
                (int) $input->getOption('threshold')
            )
        );

        $tableStyle = new TableStyle();
        $tableStyle->setPadType(\STR_PAD_LEFT);

        $table = new Table($output);
        $table->setColumnWidths(\array_fill(0, 2, 5));
        $table->setStyle($tableStyle);

        $table->setHeaders(['Count', 'Average Quantity']);

        $table->addRow([$cryptoAssetResponse->getCount(), $cryptoAssetResponse->getAverageQuantity()]);

        $table->render();

        return Command::SUCCESS;
    }
}
