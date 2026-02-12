<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Precinct;

class PrecinctCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_precinct_can_be_created()
    {
        $data = [
            'name' => 'Centro',
            'location' => 'Ciudad Central',
            'code' => 'C1',
        ];

        $precinct = Precinct::create($data);

        $this->assertDatabaseHas('precincts', $data);
        $this->assertNotNull($precinct->id);
    }

    public function test_precinct_can_be_read()
    {
        $precinct = Precinct::create(['name' => 'Centro', 'location' => 'Ciudad Central', 'code' => 'C1']);
        $fetched = Precinct::find($precinct->id);

        $this->assertNotNull($fetched);
        $this->assertEquals($precinct->name, $fetched->name);
        $this->assertEquals($precinct->location, $fetched->location);
        $this->assertEquals($precinct->code, $fetched->code);
    }

    public function test_precinct_can_be_updated()
    {
        $precinct = Precinct::create(['name' => 'Centro', 'location' => 'Ciudad Central', 'code' => 'C1']);
        $precinct->update(['name' => 'Centro Alto', 'code' => 'CHA']);

        $this->assertDatabaseHas('precincts', [
            'id' => $precinct->id,
            'name' => 'Centro Alto',
            'code' => 'CHA',
        ]);
    }

    public function test_precinct_can_be_deleted()
    {
        $precinct = Precinct::create(['name' => 'Centro', 'location' => 'Ciudad Central', 'code' => 'C1']);
        $id = $precinct->id;
        $precinct->delete();

        $this->assertDatabaseMissing('precincts', ['id' => $id]);
    }

    public function test_precinct_can_have_tables()
    {
        $precinct = Precinct::create(['name' => 'P-01', 'location' => 'Zona A']);
        $table = $precinct->tables()->create(['number' => 1]);

        $this->assertDatabaseHas('tables', [
            'id' => $table->id,
            'precinct_id' => $precinct->id,
            'number' => 1,
        ]);
        $this->assertEquals($precinct->id, $table->precinct_id);
    }
}
