<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        User::create([
            'name' => 'Sarah Jenkins',
            'email' => 'admin@apexerp.com',
            'password' => bcrypt('password'),
        ]);
    }

    /**
     * Test that root redirects to login.
     */
    public function test_root_redirects_to_login(): void
    {
        $response = $this->get('/');
        $response->assertRedirect('/login');
    }

    /**
     * Test custom login page loads successfully.
     */
    public function test_login_page_renders_successfully(): void
    {
        $response = $this->get('/login');
        $response->assertStatus(200);
        $response->assertSee('Welcome Back');
        $response->assertSee('Sign In to ERP Portal');
        $response->assertSee('APEX ERP');
    }

    /**
     * Test login with valid credentials.
     */
    public function test_login_with_valid_credentials(): void
    {
        $response = $this->post('/login', [
            'email' => 'admin@apexerp.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/admin');
        $this->assertAuthenticated();
    }

    /**
     * Test login with invalid credentials.
     */
    public function test_login_with_invalid_credentials(): void
    {
        $response = $this->from('/login')->post('/login', [
            'email' => 'admin@apexerp.com',
            'password' => 'wrong-password',
        ]);

        $response->assertRedirect('/login');
        $response->assertSessionHasErrors('email');
        $this->assertGuest();
    }

    /**
     * Test logout redirects to login page.
     */
    public function test_logout_redirects_to_login(): void
    {
        $user = User::where('email', 'admin@apexerp.com')->first();
        $response = $this->actingAs($user)->get('/logout');

        $response->assertRedirect('/login');
        $this->assertGuest();
    }

    /**
     * Test admin dashboard returns successful response.
     */
    public function test_admin_dashboard_returns_success(): void
    {
        $response = $this->get('/admin');
        $response->assertStatus(200);
        $response->assertSee('APEX ERP');
    }

    /**
     * Test admin inventory page returns success.
     */
    public function test_admin_inventory_returns_success(): void
    {
        $response = $this->get('/admin/inventory');
        $response->assertStatus(200);
        $response->assertSee('Inventory & Stock Management');
    }

    /**
     * Test admin orders page returns success.
     */
    public function test_admin_orders_returns_success(): void
    {
        $response = $this->get('/admin/orders');
        $response->assertStatus(200);
        $response->assertSee('Sales Orders');
    }

    /**
     * Test admin customers page returns success.
     */
    public function test_admin_customers_returns_success(): void
    {
        $response = $this->get('/admin/customers');
        $response->assertStatus(200);
        $response->assertSee('Customers & CRM');
    }

    /**
     * Test admin settings page returns success.
     */
    public function test_admin_settings_returns_success(): void
    {
        $response = $this->get('/admin/settings');
        $response->assertStatus(200);
        $response->assertSee('ERP System Settings');
    }

    /**
     * Test DomPDF invoice download generates a valid PDF response.
     */
    public function test_dompdf_invoice_download(): void
    {
        $response = $this->get('/admin/orders/SO-2026-0042/download-pdf');
        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    /**
     * Test system audit and activity logs page loads.
     */
    public function test_admin_activity_log_page(): void
    {
        $response = $this->get('/admin/activity-log');
        $response->assertStatus(200);
        $response->assertSee('System Audit & Activity Logs');
    }

    /**
     * Test Spatie Roles and Permissions management page loads.
     */
    public function test_admin_roles_page(): void
    {
        $response = $this->get('/admin/roles');
        $response->assertStatus(200);
        $response->assertSee('Roles & Permissions Management');
    }

    /**
     * Test Spatie Role assignment on User model.
     */
    public function test_spatie_role_assignment_on_user(): void
    {
        $role = \Spatie\Permission\Models\Role::create(['name' => 'Store Keeper']);
        $user = User::where('email', 'admin@apexerp.com')->first();
        $user->assignRole($role);

        $this->assertTrue($user->hasRole('Store Keeper'));
    }

    /**
     * Test Spatie Activitylog creation.
     */
    public function test_spatie_activity_logging(): void
    {
        $user = User::where('email', 'admin@apexerp.com')->first();
        activity()->causedBy($user)->log('Stock item adjusted manually');

        $log = \Spatie\Activitylog\Models\Activity::where('description', 'Stock item adjusted manually')->first();
        $this->assertNotNull($log);
        $this->assertEquals('Stock item adjusted manually', $log->description);
    }
}
