<?php


use App\DTO\AnimalDTO;
use App\DTO\AppointmentDTO;
use App\DTO\ClientDTO;
use PHPUnit\Framework\TestCase;

class DTOTest extends TestCase
{
    const RANDOM_STRING = 'Random String';
    const ORDER = 1;

    public function test_animal_dto(): void
    {
        $data = [
            'name' => self::RANDOM_STRING,
            'species' => self::RANDOM_STRING,
            'birthdate' => self::RANDOM_STRING,
            'client_id' => 1
        ];
        $dto = new AnimalDTO(...$data);

        $this->assertIsArray($arr = $dto->toArray());
        $this->assertEquals($data, $arr);
        $this->assertEquals(self::RANDOM_STRING, $dto->name);
        $this->assertEquals($data['name'], $dto->name);
    }

    public function test_client_dto(): void
    {
        $data = [
            'name' => self::RANDOM_STRING,
            'email' => self::RANDOM_STRING,
        ];

        $dto = new ClientDTO(...$data);

        $this->assertIsArray($arr = $dto->toArray());
        $this->assertEquals($data, $arr);
        $this->assertEquals(self::RANDOM_STRING, $dto->name);
    }

    public function test_appointment_dto(): void
    {
        $data = [
            'symptoms' => self::RANDOM_STRING,
            'appointment_date' => self::RANDOM_STRING,
            'appointment_time' => self::RANDOM_STRING,
            'animal_id' => 1,
            'user_id' => 1,
        ];
        $dto = new AppointmentDTO(...$data);

        $this->assertIsArray($arr = $dto->toArray());
        $this->assertEquals($data, $arr);
        $this->assertEquals(self::RANDOM_STRING, $dto->symptoms);
        $this->assertEquals($data['symptoms'], $dto->symptoms);
    }
}
