<?php

namespace Tests\Feature;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class AccessibilityTest extends DuskTestCase
{
    /** @test */
    public function site_has_no_critical_or_major_axe_violations()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->withVueTest(function (Browser $browser) {
                        // The axe core test will run automatically via the frontend testing package
                        $browser->axe(); // runs axe-core and fails on any critical or major violations
                    });
        });
    }
}