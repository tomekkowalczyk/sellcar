<?php

namespace App\Facade;

use App\Decorate\BiggerWheels;
use App\Decorate\LeatherSeats;
use App\Decorate\LEDLights;
use App\Decorate\SoundSystem;
use App\Decorate\Suspension;
use App\Entity\BasicCar;
use App\Entity\BusinessCar;
use App\Entity\RSCar;
use App\Entity\SedanCar;
use App\Entity\SuvCar;

class CarFacade
{
    private $car;

    public function selectCarType($type): void
    {
        switch ($type) {
            case 'SUV':
                $this->car = new SUVCar();
                break;
            case 'Sedan':
                $this->car = new SedanCar();
                break;
            default:
                throw new \Exception("Invalid car type selected.");
        }
    }

    public function selectCarVersion($version): void
    {
        switch ($version) {
            case 'Basic':
                $this->car = new BasicCar($this->car);
                break;
            case 'Business':
                $this->car = new BusinessCar($this->car);
                break;
            case 'RS':
                $this->car = new RSCar($this->car);
                break;
            default:
                throw new \Exception("Invalid car version selected.");
        }
    }

    public function addElement($element): void
    {
        switch ($element) {
            case 'SoundSystem':
                $this->car = new SoundSystem($this->car);
                break;
            case 'BiggerWheels':
                $this->car = new BiggerWheels($this->car);
                break;
            case 'Suspension':
                $this->car = new Suspension($this->car);
                break;
            case 'LEDLights':
                $this->car = new LEDLights($this->car);
                break;
            case 'LeatherSeats':
                $this->car = new LeatherSeats($this->car);
                break;
            default:
                throw new \Exception("Invalid element selected.");
        }
    }

    public function purchaseCar(): void
    {
        echo "Car Description: " . $this->car->getDescription() . "\n";
        echo "Total Cost: " . $this->car->cost() . " PLN\n";
    }
}
