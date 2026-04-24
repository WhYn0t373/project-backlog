<?php

namespace Tests\Feature\Views\Auth;

use Tests\TestCase;

class VerifyViewTest extends TestCase
{
    /** @test */
    public function verify_view_displays_correct_content_and_resend_link()
    {
        $view = $this->view('auth.verify');

        $view->assertSee('Please verify your email')
            ->assertSee('A verification link has been sent to the email address you provided')
            ->assertSee('resend the verification email')
            ->assertSee(route('verification.resend'));
    }
}