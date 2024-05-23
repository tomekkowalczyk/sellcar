<?php
declare(strict_types=1);

namespace App\Entity;

abstract class Car
{
    private string $brand;
    private string $model;
    private string $color;
    private int $year;

    public abstract function cost();

    protected string $description = "Unknown Car";

    public function __construct(string $brand, string $model, string $color, int $year)
    {
        $this->brand = $brand;
        $this->model = $model;
        $this->color = $color;
        $this->year = $year;
    }

    public function getDescription(): string
    {
        return $this->description;
    }
}
