<?php

namespace Tests\Feature;

use App\Models\House;
use App\Models\Participant;
use App\Models\Sport;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PublicRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_participant_can_register_and_receive_a_registration_code(): void
    {
        $house = House::create(['name' => 'Rumah Hijau']);
        $sport = Sport::create(['name' => 'Balloon Rush', 'category' => 'Dewasa', 'is_active' => true]);

        $response = $this->post('/daftar', [
            'name' => 'Ali Budiman',
            'age' => 25,
            'phone' => '0123456789',
            'category' => 'Dewasa',
            'house_id' => $house->id,
            'sport_id' => $sport->id,
        ]);

        $participant = Participant::first();

        $this->assertNotNull($participant);
        $this->assertStringStartsWith('SRKB-', $participant->registration_code);
        $response->assertRedirect(route('public.success', $participant->registration_code));
    }

    public function test_child_registration_requires_guardian_details(): void
    {
        $house = House::create(['name' => 'Rumah Merah']);

        $response = $this->post('/daftar', [
            'name' => 'Amin Budiman',
            'age' => 8,
            'phone' => '0133456789',
            'category' => 'Kanak-Kanak',
            'house_id' => $house->id,
        ]);

        $response->assertSessionHasErrors(['guardian_name', 'guardian_phone', 'guardian_relationship']);
    }
}
