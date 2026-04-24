<?php

namespace Tests\Feature\Views\Auth;

use Tests\TestCase;

class LoginViewTest extends TestCase
{
    /** @test */
    public function login_view_displays_verification_status_message()
    {
        $view = $this->withSession(['status' => 'verification-link-sent'])
            ->view('auth.login');

        $view->assertSee('A verification email has been sent to your email address. Please verify before logging in.');
    }
}