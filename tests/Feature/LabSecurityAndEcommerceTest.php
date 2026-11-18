<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LabSecurityAndEcommerceTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    /**
     * Requirement: APP_DEBUG=false
     */
    public function test_app_debug_is_disabled(): void
    {
        $this->assertFalse(config('app.debug'), 'APP_DEBUG should be false for production lab security.');
    }

    /**
     * Requirement: Middleware auth terpasang di semua route yang perlu login.
     */
    public function test_guest_is_redirected_to_login_for_protected_routes(): void
    {
        $this->get('/cart')->assertRedirect('/login');
        $this->post('/cart/add', ['product_id' => 1])->assertRedirect('/login');
        $this->get('/checkout')->assertRedirect('/login');
        $this->post('/checkout', [])->assertRedirect('/login');
        $this->get('/profile')->assertRedirect('/login');
        $this->put('/profile', [])->assertRedirect('/login');
    }

    /**
     * Requirement: Pesan error login/register digeneralisasi ("email atau password salah", tanpa membedakan mana yang salah).
     */
    public function test_login_error_message_is_generalized_for_web(): void
    {
        $generalizedMessage = 'Email atau password salah.';

        // 1. Non-existent email
        $response1 = $this->post('/login', [
            'email' => 'tidakada@tokokita.lab',
            'password' => 'wrongpass123',
        ]);
        $response1->assertSessionHasErrors(['auth_error' => $generalizedMessage]);

        // 2. Existing email but wrong password
        $user = User::first();
        $response2 = $this->post('/login', [
            'email' => $user->email,
            'password' => 'wrongpasswordtotally',
        ]);
        $response2->assertSessionHasErrors(['auth_error' => $generalizedMessage]);
    }

    /**
     * Requirement: Pesan error login/register digeneralisasi pada API dan registrasi duplikat.
     */
    public function test_login_and_registration_error_is_generalized_for_api(): void
    {
        $generalizedMessage = 'Email atau password salah.';

        // API login with incorrect password
        $user = User::first();
        $response = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrongpass',
        ]);
        $response->assertStatus(401);
        $this->assertEquals($generalizedMessage, $response->json('message'));

        // API registration with already registered email
        $regResponse = $this->postJson('/api/register', [
            'name' => 'Clone User',
            'email' => $user->email, // Existing email
            'password' => 'password12345',
        ]);
        $regResponse->assertStatus(422);
        $this->assertEquals($generalizedMessage, $regResponse->json('message'));

        // Web registration with already registered email
        $webReg = $this->post('/register', [
            'name' => 'Another User',
            'email' => $user->email,
            'password' => 'secret1234',
            'password_confirmation' => 'secret1234',
        ]);
        $webReg->assertSessionHasErrors(['auth_error' => $generalizedMessage]);
    }

    /**
     * Requirement: Endpoint /api/users/{id} hanya mengembalikan data milik user yang sedang login (cek Auth::id() === $id), 401/403 untuk yang lain.
     */
    public function test_api_user_endpoint_prevents_idor(): void
    {
        $user1 = User::find(1);
        $user2 = User::find(2);

        // 1. Unauthenticated request -> 401
        $this->getJson("/api/users/{$user1->id}")
            ->assertStatus(401);

        // 2. Authenticated as user1 requesting user2's data -> 403 Forbidden
        Sanctum::actingAs($user1);
        $responseForbidden = $this->getJson("/api/users/{$user2->id}");
        $responseForbidden->assertStatus(403);
        $this->assertStringContainsString('Akses ditolak', $responseForbidden->json('message'));

        // 3. Authenticated as user1 requesting user1's own data -> 200 OK
        $responseOk = $this->getJson("/api/users/{$user1->id}");
        $responseOk->assertStatus(200);
        $this->assertEquals($user1->id, $responseOk->json('data.id'));
        $this->assertEquals($user1->email, $responseOk->json('data.email'));
        $this->assertArrayNotHasKey('password', $responseOk->json('data'));
    }

    /**
     * Requirement: Seeder 20 produk, 15 user dengan nama/email/no.telp/alamat fiktif.
     */
    public function test_seeders_populated_required_data(): void
    {
        $this->assertGreaterThanOrEqual(15, User::count());
        $this->assertGreaterThanOrEqual(20, Product::count());

        $firstUser = User::first();
        $this->assertNotEmpty($firstUser->name);
        $this->assertNotEmpty($firstUser->email);
        $this->assertNotEmpty($firstUser->phone);
        $this->assertNotEmpty($firstUser->address);
    }

    /**
     * Requirement: Fitur e-commerce (katalog, keranjang, checkout sederhana, profil).
     */
    public function test_full_ecommerce_workflow(): void
    {
        $user = User::first();
        $product = Product::latest()->first();

        // 1. View products catalog
        $this->get('/products')->assertStatus(200)->assertSee($product->name);

        // 2. Add product to cart
        $this->actingAs($user)->post('/cart/add', [
            'product_id' => $product->id,
            'quantity' => 2,
        ])->assertRedirect('/cart');

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'product_id' => $product->id,
            'quantity' => 2,
        ]);

        // 3. View cart
        $this->actingAs($user)->get('/cart')
            ->assertStatus(200)
            ->assertSee($product->name);

        // 4. View checkout
        $this->actingAs($user)->get('/checkout')
            ->assertStatus(200)
            ->assertSee('Checkout Pesanan');

        // 5. Submit checkout
        $initialStock = $product->stock;
        $checkoutResponse = $this->actingAs($user)->post('/checkout', [
            'shipping_name' => 'Lab Tester',
            'shipping_phone' => '089988776655',
            'shipping_address' => 'Gedung Lab Jaringan Lt. 3',
            'payment_method' => 'Transfer Bank',
            'notes' => 'Tolong kirim siang hari',
        ]);

        $order = Order::where('user_id', $user->id)->latest()->first();
        $this->assertNotNull($order);
        $checkoutResponse->assertRedirect("/orders/{$order->id}");

        // Cart is cleared and stock decremented
        $this->assertDatabaseMissing('cart_items', ['user_id' => $user->id]);
        $this->assertEquals($initialStock - 2, $product->fresh()->stock);

        // 6. User can view their own order
        $this->actingAs($user)->get("/orders/{$order->id}")
            ->assertStatus(200)
            ->assertSee($order->order_number);

        // 7. Another user CANNOT view this order (403 IDOR prevention)
        $anotherUser = User::find(2);
        $this->actingAs($anotherUser)->get("/orders/{$order->id}")
            ->assertStatus(403);

        // 8. View Profile
        $this->actingAs($user)->get('/profile')
            ->assertStatus(200)
            ->assertSee($user->name)
            ->assertSee($order->order_number);
    }

    /**
     * Requirement: Artisan command "tokokita:export-backup"
     */
    public function test_tokokita_export_backup_command(): void
    {
        $backupDir = storage_path('backup');

        $this->artisan('tokokita:export-backup')
            ->assertSuccessful();

        $files = File::files($backupDir);
        $this->assertNotEmpty($files, 'Backup file should exist in storage/backup/');

        $latestSqlFile = $files[0]->getPathname();
        $this->assertStringEndsWith('.sql', $latestSqlFile);

        $sqlContent = File::get($latestSqlFile);
        $this->assertStringContainsString('users', $sqlContent);
        $this->assertStringContainsString('products', $sqlContent);
    }
}
