<?php

declare(strict_types=1);

namespace App\Commands;

use App\Facade\CarConfiguratorFacade;
use App\Model\Enum\CarType;
use App\Model\Enum\CarVersion;
use App\Observe\CarOrderObserver;
use App\Repositories\CsvCarRepository;
use App\Strategy\EamailNotificationStrategy;
use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;

use function Termwind\{render};

class ConfigureCarCommand extends Command
{
    /**
     * The signature of the command.
     *
     * @var string
     */
    protected $signature = 'configureCar {name=Artisan}';

    /**
     * The description of the command.
     *
     * @var string
     */
    protected $description = 'Configure your car:';

    /**
     * Execute the console command.
     * @throws \Exception
     */
    public function handle(): void
    {
        $facade = new CarConfiguratorFacade();
        $emailStrategy = new EamailNotificationStrategy();
        $carOrderObserver = new CarOrderObserver($emailStrategy);
        $facade->attach($carOrderObserver);

        $type = $this->choice('Wybierz typ pojazdu:', CarType::getValues(), 0);
        $carType = CarType::fromString($type);
        $facade->selectCarType($carType);

        $version = $this->choice('Wybierz wersję pojazdu:', CarVersion::getValues(), 0);
        $carVersion = CarVersion::fromString($version);
        $facade->selectCarVersion($carVersion);

        $elements = ['SoundSystem', 'BiggerWheels', 'Suspension', 'LEDLights', 'LeatherSeats'];
        while ($this->confirm('Czy chcesz dodać element do konfiguracji?')) {
            $element = $this->choice('Wybierz element do dodania:', $elements, 0);
            $facade->addElement($element);
            $elements[] = $element;
        }

        $facade->purchaseCar();
        $costs = $facade->car->cost();

        $csvSave = new CsvCarRepository();
        $csvSave->save([
            'carType' => $carType->value,
            'carVersion' => $carVersion->value,
            'elements' => $elements,
            'costs' => $costs,
            'status' => 'New'
        ]);

        render(<<<'HTML'
            <div class="py-1 ml-2">
                <div class="px-1 bg-blue-300 text-black">🚗</div>
                <em class="ml-1">
                  Car is configured.
                </em>
            </div>
        HTML);
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
    }
}
