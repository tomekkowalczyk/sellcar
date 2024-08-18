<?php

declare(strict_types=1);

namespace App\Commands;

use App\Repositories\CsvCarRepository;
use Illuminate\Console\Command;
use League\Csv\Exception;
use League\Csv\UnavailableStream;
use Symfony\Component\Console\Helper\Table;

class ManageReservationCommand extends Command
{
    protected $signature = 'reservations:manage';
    protected $description = 'Wyświetl i edytuj listę rezerwacji';
    protected $repository;

    public function __construct()
    {
        parent::__construct();
        $this->repository = new CsvCarRepository();
    }

    /**
     * @throws UnavailableStream
     * @throws Exception
     */
    public function handle(): void
    {
        $reservations = $this->repository->getAll();

        if (empty($reservations)) {
            $this->info('Brak rezerwacji.');
            return;
        }

        $this->displayReservations($reservations);

        if ($this->confirm('Czy chcesz edytować rezerwację?')) {
            $reservationIndex = $this->ask('Podaj numer rezerwacji do edycji');

            if (isset($reservations[$reservationIndex - 1])) {
                $this->editReservation($reservations[$reservationIndex - 1]);
                $this->repository->saveAll($reservations);
            } else {
                $this->error('Nieprawidłowy numer rezerwacji.');
            }
        }
    }

    protected function displayReservations(array $reservations): void
    {
        $table = new Table($this->output);
        $table->setHeaders(['#', 'Car Type', 'Car Version', 'Elements', 'Costs', 'Status']);

        foreach ($reservations as $index => $reservation) {
            $table->addRow([
                $index + 1,
                $reservation['carType'],
                $reservation['carVersion'],
                $reservation['elements'],
                $reservation['costs'],
                $reservation['status'] ?? 'unknown',
            ]);
        }

        $table->render();
    }

    protected function editReservation(array &$reservation): void
    {
        $this->info("Edycja rezerwacji dla pojazdu typu: {$reservation['carType']}");

        $reservation['carType'] = $this->ask('Typ pojazdu', $reservation['carType']);
        $reservation['carVersion'] = $this->ask('Wersja pojazdu', $reservation['carVersion']);

        $elements = explode(',', $reservation['elements']);
        $this->info("Obecne elementy: " . implode(', ', $elements));

        if ($this->confirm('Czy chcesz zmienić elementy?')) {
            $elements = [];
            $availableElements = ['SoundSystem', 'BiggerWheels', 'Suspension', 'LEDLights', 'LeatherSeats'];
            while ($this->confirm('Czy chcesz dodać element do konfiguracji?')) {
                $element = $this->choice('Wybierz element do dodania:', $availableElements, 0);
                $elements[] = $element;
            }
        }
        $reservation['elements'] = implode(',', $elements);

        $reservation['costs'] = $this->ask('Koszt', $reservation['costs']);
        $reservation['status'] = $this->ask('Status', $reservation['status'] ?? 'unknown');

        $this->info('Rezerwacja została zaktualizowana.');
    }
}
