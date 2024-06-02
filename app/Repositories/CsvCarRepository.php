<?php

declare(strict_types=1);

namespace App\Repositories;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use League\Csv\CannotInsertRecord;
use League\Csv\Exception;
use League\Csv\Reader;
use League\Csv\UnavailableStream;
use League\Csv\Writer;
use SplTempFileObject;

class CsvCarRepository implements CarRepository
{
    private const FILE_PATH = 'reservations.csv';

    protected ?string $carVersion = null;
    protected ?string $carType = null;
    protected array $elements = [];

    public function filterByCarVersion(?string $version): self
    {
        $this->carVersion = $version;
        return $this;
    }

    public function filterByCarType(?string $type): self
    {
        $this->carType = $type;
        return $this;
    }

    public function filterByElements(array $elements): self
    {
        $this->elements = $elements;
        return $this;
    }

    /**
     * @throws UnavailableStream
     * @throws CannotInsertRecord
     * @throws Exception
     */
    public function save(array $data): void
    {
        $disk = Storage::disk('local');

        if ($disk->exists(self::FILE_PATH)) {
            $csv = Writer::createFromPath($disk->path(self::FILE_PATH), 'a+');
        } else {
            $csv = Writer::createFromFileObject(new SplTempFileObject());
            $csv->insertOne(['carType', 'carVersion', 'elements', 'costs']);
            $disk->put(self::FILE_PATH, $csv->toString());
            $csv = Writer::createFromPath($disk->path(self::FILE_PATH), 'a+');
        }

        $csv->insertOne([
            $data['carType'],
            $data['carVersion'],
            implode(',', $data['elements']),
            $data['costs']
        ]);
    }

    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    public function get(): Collection
    {
        if (!Storage::disk('local')->exists(self::FILE_PATH)) {
            throw new \Exception("CSV file does not exist.");
        }

        $csv = Reader::createFromPath(Storage::disk('local')->path(self::FILE_PATH), 'r');
        $csv->setHeaderOffset(0);

        $records = collect($csv->getRecords());

        return $records->filter(function ($record) {
            return $this->filterRecord($record);
        });
    }

    protected function filterRecord(array $record): bool
    {
        if ($this->carVersion && $record['carVersion'] !== $this->carVersion) {
            return false;
        }

        if ($this->carType && $record['carType'] !== $this->carType) {
            return false;
        }

        if ($this->elements) {
            $recordElements = explode(',', $record['elements']);
            foreach ($this->elements as $element) {
                if (!in_array($element, $recordElements)) {
                    return false;
                }
            }
        }

        return true;
    }
}
