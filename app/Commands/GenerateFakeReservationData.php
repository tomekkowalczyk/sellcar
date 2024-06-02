<?php

namespace App\Commands;

use App\Model\Enum\CarType;
use App\Model\Enum\CarVersion;
use Illuminate\Console\Command;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use SplTempFileObject;

class GenerateFakeReservationData extends Command
{
    protected $signature = 'generate:fake-reservations-data {count=10 : Number of records to generate}';
    protected $description = 'Generate fake reservation data and save to a CSV file';

    public function handle(): void
    {
        $count = (int)$this->argument('count');
        $faker = Faker::create();

        $filePath = 'reservations.csv';
        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->insertOne(['carType', 'carVersion', 'elements', 'costs']);

        $carTypes = CarType::getValues();
        $carVersions = CarVersion::getValues();
        $elementsArray = ['SoundSystem', 'BiggerWheels', 'Suspension', 'LEDLights', 'LeatherSeats'];

        for ($i = 0; $i < $count; $i++) {
            $carType = $faker->randomElement($carTypes);
            $carVersion = $faker->randomElement($carVersions);
            $elements = $faker->randomElements($elementsArray, $faker->numberBetween(1, 3));
            $costs = $faker->numberBetween(20000, 150000);

            $csv->insertOne([
                $carType,
                $carVersion,
                implode(',', $elements),
                $costs
            ]);
        }

        Storage::disk('local')->put($filePath, $csv->toString());

        $this->info("Fake reservations data generated and saved to {$filePath}");
    }
}
