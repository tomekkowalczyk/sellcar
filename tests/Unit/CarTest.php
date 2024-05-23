<?php

use App\Entity\BasicCar;
use App\Entity\BusinessCar;
use App\Entity\RSCar;
use App\Entity\SuvCar;

it('can create a basic SUV car', function () {
    $car = new BasicCar(new SuvCar());
    expect($car->getDescription())->toBe('SUV Basic');
    expect($car->cost())->toBe(80000);
});

it('can create a business SUV car', function () {
    $car = new BusinessCar(new SuvCar());
    expect($car->getDescription())->toBe('SUV Business');
    expect($car->cost())->toBe(110000);
});

it('can create an RS SUV car', function () {
    $car = new RSCar(new SuvCar());
    expect($car->getDescription())->toBe('SUV RS');
    expect($car->cost())->toBe(150000);
});
