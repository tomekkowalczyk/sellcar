<?php

declare(strict_types=1);

namespace App\Facade;

use App\Decorate\BiggerWheels;
use App\Decorate\LeatherSeats;
use App\Decorate\LEDLights;
use App\Decorate\SoundSystem;
use App\Decorate\Suspension;
use App\Model\Entity\BasicCar;
use App\Model\Entity\BusinessCar;
use App\Model\Entity\Car;
use App\Model\Entity\RSCar;
use App\Model\Entity\SedanCar;
use App\Model\Entity\SuvCar;
use App\Model\Enum\CarType;
use App\Model\Enum\CarVersion;

class CarConfiguratorFacade
{
    public Car $car;

    /**
     * @throws \Exception
     */
    public function selectCarType(CarType $type): void
    {
        $this->car = match ($type) {
            CarType::SUV => new SUVCar(),
            CarType::SEDAN => new SedanCar(),
            default => throw new \Exception("Invalid car type selected."),
        };
    }

    /**
     * @throws \Exception
     */
    public function selectCarVersion(CarVersion $version): void
    {
        $this->car = match ($version) {
            CarVersion::BASIC => new BasicCar($this->car),
            CarVersion::BUSINESS => new BusinessCar($this->car),
            CarVersion::RS => new RSCar($this->car),
            default => throw new \Exception("Invalid car version selected."),
        };
    }

    public function addElement(string $element): void
    {
        $this->car = match ($element) {
            'SoundSystem' => new SoundSystem($this->car),
            'BiggerWheels' => new BiggerWheels($this->car),
            'Suspension' => new Suspension($this->car),
            'LEDLights' => new LEDLights($this->car),
            'LeatherSeats' => new LeatherSeats($this->car),
            default => throw new \Exception("Invalid element selected."),
        };
    }

    public function purchaseCar(): void
    {
        echo "Car Description: " . $this->car->getDescription() . "\n";
        echo "Total Cost: " . $this->car->cost() . " PLN\n";
    }
}
