<?php

declare(strict_types=1);

namespace App\Builders;

use App\Repositories\CsvCarRepository;
use Illuminate\Support\Collection;
use League\Csv\Exception;
use League\Csv\UnavailableStream;

class CsvQueryBuilder
{
    protected CsvCarRepository $repository;

    public function __construct(CsvCarRepository $repository)
    {
        $this->repository = $repository;
    }

    public function whereCarVersion(string $version): self
    {
        $this->repository->filterByCarVersion($version);
        return $this;
    }

    public function whereCarType(string $type): self
    {
        $this->repository->filterByCarType($type);
        return $this;
    }

    public function whereElements(array $elements): self
    {
        $this->repository->filterByElements($elements);
        return $this;
    }

    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    public function get(): Collection
    {
        return $this->repository->get();
    }
}

