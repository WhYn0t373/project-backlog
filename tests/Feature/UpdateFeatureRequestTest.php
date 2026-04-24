<?php

namespace Tests\Feature;

use App\Http\Requests\UpdateFeatureRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class UpdateFeatureRequestTest extends TestCase
{
    /** @test */
    public function it_validates_name_and_description()
    {
        $data = ['name' => 'Update', 'description' => 'Desc'];
        $validator = Validator::make($data, (new UpdateFeatureRequest())->rules());

        $this->assertFalse($validator->fails());

        $dataInvalid = ['description' => 'Desc']; // missing name
        $validator = Validator::make($dataInvalid, (new UpdateFeatureRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->messages());
    }
}