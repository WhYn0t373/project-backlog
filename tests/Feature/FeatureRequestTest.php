<?php

namespace Tests\Feature;

use App\Http\Requests\FeatureRequest;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class FeatureRequestTest extends TestCase
{
    /**
     * Test that the FeatureRequest validation rules are correct.
     *
     * @return void
     */
    public function test_feature_request_rules()
    {
        $rules = (new FeatureRequest)->rules();

        $this->assertArrayHasKey('name', $rules);
        $this->assertArrayHasKey('description', $rules);

        $this->assertEquals('required|string|max:255', $rules['name']);
        $this->assertEquals('nullable|string', $rules['description']);
    }

    /**
     * Test that a valid payload passes validation.
     *
     * @return void
     */
    public function test_valid_payload_passes_validation()
    {
        $data = [
            'name' => 'Valid Feature',
            'description' => 'Some description',
        ];

        $validator = Validator::make($data, (new FeatureRequest)->rules());

        $this->assertFalse($validator->fails());
    }

    /**
     * Test that an invalid payload fails validation.
     *
     * @return void
     */
    public function test_invalid_payload_fails_validation()
    {
        $data = [
            'name' => str_repeat('a', 300), // Exceeds max:255
        ];

        $validator = Validator::make($data, (new FeatureRequest)->rules());

        $this->assertTrue($validator->fails());
        $this->assertTrue($validator->errors()->has('name'));
    }
}