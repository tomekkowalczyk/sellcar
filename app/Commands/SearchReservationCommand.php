<?php

declare(strict_types=1);

namespace App\Commands;

use App\Builders\CsvQueryBuilder;
use App\Facade\CarConfiguratorFacade;
use App\Model\Enum\CarType;
use App\Model\Enum\CarVersion;
use App\Repositories\CsvCarRepository;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Symfony\Component\Console\Helper\Table;

use function Termwind\{render};

class SearchReservationCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'searchReservation {name=Artisan}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Search reservation:';

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle(): void
    {
        $facade = new CarConfiguratorFacade();

        $values = CarType::getValues();
        array_push($values, 'Pomiń');
        $type = $this->choice('Wybierz typ pojazdu:', $values, 0);
        $carType = CarType::fromString($type);

        $values = CarVersion::getValues();
        array_push($values, 'Pomiń');
        $version = $this->choice('Wybierz wersję pojazdu:', $values, 0);
        $carVersion = CarVersion::fromString($version);

        $choiceElements = [];
        $elements = ['SoundSystem', 'BiggerWheels', 'Suspension', 'LEDLights', 'LeatherSeats'];
        while ($this->confirm('Czy chcesz dodać element do konfiguracji?')) {
            $element = $this->choice('Wybierz element do dodania:', $elements, 0);
            $choiceElements[] = $element;
        }

        $reservationRepository = new CsvCarRepository();
        $builder = new CsvQueryBuilder($reservationRepository);

        if ($carVersion) {
            $builder->whereCarVersion($carVersion->value);
        }

        if ($carType) {
            $builder->whereCarType($carType->value);
        }

        if ($choiceElements) {
            $builder->whereElements($choiceElements);
        }

        $results = $builder->get();
        $countReservation = count($results);

        $this->info("Total number of sold cars: $countReservation");

        $table = new Table($this->output);
        $table->setHeaders(['#', 'Car Type', 'Car Version', 'Elements', 'Costs']);

        foreach ($results as $index => $row) {
            $table->addRow([
                $index + 1,
                $row['carType'],
                $row['carVersion'],
                $row['elements'],
                $row['costs']
            ]);
        }

        $table->render();
    }



    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
    }
}
