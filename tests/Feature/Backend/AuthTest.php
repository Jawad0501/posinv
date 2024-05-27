<?php

namespace Tests\Feature\Backend;

use App\Models\Staff;
use App\Providers\RouteServiceProvider;
use Tests\TestCase;

class AuthTest extends TestCase
{
    /**
     * A basic feature test login_screen_can_be_rendered.
     *
     * @return void
     */
    public function test_login_screen_can_be_rendered()
    {
        $response = $this->get('/staff/login');
        $response->assertStatus(200);
    }

    /**
     * A basic feature test staff_can_authenticate_using_the_login_screen.
     *
     * @return void
     */
    public function test_staff_can_authenticate_using_the_login_screen()
    {
        $admin = Staff::find(1);

        $response = $this->post('/admin/login', [
            'email' => $admin->email,
            'password' => '12345678',
        ]);

        $this->assertAuthenticated('staff');
        $response->assertRedirect(RouteServiceProvider::ADMIN);
    }
}
