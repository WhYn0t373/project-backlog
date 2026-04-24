<?php

namespace Tests\Feature;

use App\Http\Requests\StoreFeatureRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class StoreFeatureRequestTest extends TestCase
{
    /** @test */
    public function it_validates_name_and_description()
    {
        $data = ['name' => 'Test', 'description' => 'Desc'];
        $validator = Validator::make($data, (new StoreFeatureRequest())->rules());

        $this->assertFalse($validator->fails());

        $dataInvalid = ['name' => '']; // missing name
        $validator = Validator::make($dataInvalid, (new StoreFeatureRequest())->rules());

        $this->assertTrue($validator->fails());
        $this->assertArrayHasKey('name', $validator->errors()->messages());
    }
}