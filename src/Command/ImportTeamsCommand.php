<?php

namespace App\Command;

use App\ExcelSheet;
use App\Model\Location;
use App\Model\Player;
use App\Model\PlayerPosition;
use App\Model\Team;
use App\Model\Trainer;
use Pimcore\Console\AbstractCommand;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use TypeError;

#[AsCommand(
    name: 'import:teams',
    description: 'Import Teams'
)]
class ImportTeamsCommand extends AbstractCommand
{
    private const SHEETS = [
        'trainers',
        'locations',
        'player_positions',
        'teams',
        'players',
    ];

    protected function configure(): void
    {
        $this
            ->addArgument(
                'file',
                InputArgument::REQUIRED,
                'File to import'
            )
            ->addArgument(
                'sheetName',
                InputArgument::OPTIONAL,
                'Sheet name'
            );
    }

    protected function execute(
        InputInterface $input,
        OutputInterface $output
    ): int {
        if (!str_ends_with($input->getArgument('file'), '.xlsx')) {
            $output->writeln('<error>File extension has to be .xlsx!</error>');

            return self::INVALID;
        }

        $list = Team\Listing::getItems();
        $list->setCondition('founded_at = 1933');
        $result = $list->load();

        dd($result);

        $rootPath = __DIR__ . '/../..';
        $filePath = $rootPath . DIRECTORY_SEPARATOR . $input->getArgument('file');

        $this->importData($filePath);

        return self::SUCCESS;
    }

    private function importData(string $filePath): void
    {
        $sheet = ExcelSheet::getInstance();
        $sheets = $sheet->getAvailableSheets($filePath, self::SHEETS);

        foreach ($sheets as $sheetName => $isAvailable) {
            if (!$isAvailable) {
                $this->output->writeln('<bg=yellow;options=bold>Sheet ' . $sheetName . ' is not available. Skipping.</>');
                continue;
            }

            $limit = 20;
            $offset = 1;

            $total = $sheet->getTotalRowCount($filePath, $sheetName);
            $importedItems = 0;
            while ($total > $offset) {
                $rows = $sheet->getRows($filePath, $offset, $limit, $sheetName);

                $createMethod = 'create' . ucfirst($sheetName) . 'Entity';
                foreach ($rows as $row) {
                    try {
                        $this->{$createMethod}($row);

                        $importedItems++;
                    } catch (TypeError) {
                        // Ignore.
                    } catch (\Throwable $e) {
                        throw $e; // TODO: Catch exception
                    }
                }

                $offset += $limit;
            }

            $this->output->writeln('<info>Imported ' . $importedItems . ' ' . $sheetName . '!</info>');
        }
    }

    private function createLocationsEntity(array $data): void
    {
        $id = $data['id'] ?? null;

        $location = null;
        if ($id) {
            $location = Location::getById($id);
        }

        if (!$location) {
            $location = new Location();
        }

        $location->setName($data['name']);
        $location->setLat($data['lat']);
        $location->setLon($data['lon']);
        $location->save();
    }

    private function createTrainersEntity(array $data): void
    {
        $id = $data['id'] ?? null;

        $trainer = null;
        if ($id) {
            $trainer = Trainer::getById($id);
        }

        if (!$trainer) {
            $trainer = new Trainer();
        }

        $trainer->setFirstName($data['first_name']);
        $trainer->setLastName($data['last_name']);

        $trainer->save();
    }

    private function createPlayer_positionsEntity(array $data): void
    {
        $id = $data['id'] ?? null;

        $playerPosition = null;
        if ($id) {
            $playerPosition = PlayerPosition::getById($id);
        }

        if (!$playerPosition) {
            $playerPosition = new PlayerPosition();
        }

        $playerPosition->setName($data['name']);

        $playerPosition->save();
    }

    private function createTeamsEntity(array $data): void
    {
        $id = $data['id'] ?? null;

        $team = null;
        if ($id) {
            $team = Team::getById($id);
        }

        if (!$team) {
            $team = new Team();
        }

        if ((int)$data['trainer'] !== 0) {
            $trainer = Trainer::getById($data['trainer']);
        } else {
            $trainer = Trainer::getByName($data['trainer']);
        }
        $team->setTrainerId($trainer->getId());

        if ((int)$data['location'] !== 0) {
            $location = Location::getById($data['location']);
        } else {
            $location = Location::getByName($data['location']);
        }
        $team->setLocationId($location->getId());

        $team->setName($data['name']);
        $team->setLogo($data['logo']);
        $team->setFoundedAt($data['founded_at']);

        $team->save();
    }

    private function createPlayersEntity(array $data): void
    {
        $id = $data['id'] ?? null;

        $player = null;
        if ($id) {
            $player = Player::getById($id);
        }

        if (!$player) {
            $player = new Player();
        }

        if ((int)$data['position'] !== 0) {
            $playerPosition = PlayerPosition::getById($data['position']);
        } else {
            $playerPosition = PlayerPosition::getByName($data['position']);
        }
        $player->setPositionId($playerPosition->getId());

        if ((int)$data['team'] !== 0) {
            $team = Team::getById($data['team']);
        } else {
            $team = Team::getByName($data['team']);
        }
        $player->setTeamId($team->getId());

        $player->setFirstName($data['first_name']);
        $player->setLastName($data['last_name']);
        $player->setFieldNumber($data['field_number']);
        $player->setAge($data['age']);

        $player->save();
    }
}
