<?php

namespace Tests\Feature;

use App\Repositories\CsvCarRepository;
use Illuminate\Support\Facades\Storage;
use League\Csv\Writer;
use SplTempFileObject;
use Tests\TestCase;

class CsvCarRepositoryTest extends TestCase
{
    private const FILE_PATH = 'reservations.csv';

    public function setUp(): void
    {
        parent::setUp();
        Storage::fake('local');
        if (Storage::disk('local')->exists(self::FILE_PATH)) {
            Storage::disk('local')->delete(self::FILE_PATH);
        }
    }

    public function testSaveCreatesNewFileIfNotExists()
    {
        $data = [
            'carType' => 'SUV',
            'carVersion' => '2023',
            'elements' => ['SoundSystem', 'BiggerWheels'],
            'costs' => 150000
        ];

        $repository = new CsvCarRepository();
        $repository->save($data);

        Storage::disk('local')->assertExists(self::FILE_PATH);

        $fileContent = Storage::disk('local')->get(self::FILE_PATH);
        $expectedCsvContent = <<<CSV
carType,carVersion,elements,costs
SUV,2023,"SoundSystem,BiggerWheels",150000

CSV;

        $this->assertEquals($expectedCsvContent, $fileContent);
    }

    public function testSaveAppendsToFileIfExists()
    {
        $initialData = [
            'carType' => 'Sedan',
            'carVersion' => '2022',
            'elements' => ['LeatherSeats'],
            'costs' => 100000
        ];

        $repository = new CsvCarRepository();
        $repository->save($initialData);

        $newData = [
            'carType' => 'SUV',
            'carVersion' => '2023',
            'elements' => ['SoundSystem', 'BiggerWheels'],
            'costs' => 150000
        ];

        $repository->save($newData);

        $fileContent = Storage::disk('local')->get(self::FILE_PATH);
        $expectedCsvContent = <<<CSV
carType,carVersion,elements,costs
Sedan,2022,LeatherSeats,100000
SUV,2023,"SoundSystem,BiggerWheels",150000

CSV;

        $this->assertEquals($expectedCsvContent, $fileContent);
    }

    public function testGetThrowsExceptionIfFileDoesNotExist()
    {
        $this->expectException(\Exception::class);
        $this->expectExceptionMessage("CSV file does not exist.");

        $repository = new CsvCarRepository();
        $repository->get();
    }

    public function testGetReturnsFilteredRecords()
    {
        $initialData = [
            ['carType' => 'Sedan', 'carVersion' => '2022', 'elements' => 'LeatherSeats', 'costs' => 100000],
            ['carType' => 'SUV', 'carVersion' => '2023', 'elements' => 'SoundSystem,BiggerWheels', 'costs' => 150000],
            ['carType' => 'Truck', 'carVersion' => '2021', 'elements' => 'Suspension,LEDLights', 'costs' => 200000]
        ];

        $csv = Writer::createFromFileObject(new SplTempFileObject());
        $csv->insertOne(['carType', 'carVersion', 'elements', 'costs']);
        foreach ($initialData as $data) {
            $csv->insertOne($data);
        }
        Storage::disk('local')->put(self::FILE_PATH, $csv->toString());

        $repository = new CsvCarRepository();
        $repository->filterByCarType('SUV')->filterByElements(['SoundSystem']);

        $records = $repository->get();

        $this->assertCount(1, $records);
        $this->assertEquals('SUV', $records->first()['carType']);
        $this->assertEquals('2023', $records->first()['carVersion']);
        $this->assertEquals('SoundSystem,BiggerWheels', $records->first()['elements']);
    }
}
