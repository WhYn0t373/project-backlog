<?php

namespace Tests\Unit;

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Contracts\Console\Kernel as ConsoleKernel;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Tests\TestCase;

class ApplicationTest extends TestCase
{
    /** @test */
    public function application_container_has_expected_singletons()
    {
        $app = $this->app;

        $this->assertInstanceOf(Kernel::class, $app->make(Kernel::class));
        $this->assertInstanceOf(ConsoleKernel::class, $app->make(ConsoleKernel::class));
        $this->assertInstanceOf(ExceptionHandler::class, $app->make(ExceptionHandler::class));
    }
}