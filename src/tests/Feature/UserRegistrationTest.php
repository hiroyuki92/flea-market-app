<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_page_is_accessible()
    {
        $response = $this->get('/register');
        $response->assertStatus(200);
    }

    public function test_name_is_required_for_registration()
    {
        $response = $this->post('/register', [
            'name' => '', // 空のデータを送信
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHasErrors([
            'name' => 'お名前を入力してください'
        ]);
    }

    public function test_email_is_required_for_registration()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => '', // 空のデータを送信
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['email' => 'メールアドレスを入力してください']);
    }

    public function test_password_is_required_for_registration()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => '', // 空のデータを送信
            'password_confirmation' => 'password123'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['password'=> 'パスワードを入力してください']);
    }

    public function test_password_must_be_at_least_8_characters()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'short', // 7文字未満のパスワード
            'password_confirmation' => 'short'
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['password' => 'パスワードは8文字以上で入力してください']);
    }

    public function test_password_must_be_confirmed()
    {
        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password123', // パスワード
            'password_confirmation' => 'different_password' // 異なる確認用パスワード
        ]);

        $response->assertStatus(302);
        $response->assertRedirect();
        $response->assertSessionHasErrors(['password' => 'パスワードと一致しません。']);
    }

    public function test_user_can_register_successfully()
    {
        $userData = [
            'name' => '山田 太郎',
            'email' => 'test@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123'
        ];

        $response = $this->post('/register', $userData);

        // データベースにユーザーが登録されたことを確認
        $this->assertDatabaseHas('users', [
            'name' => $userData['name'],
            'email' => $userData['email']
        ]);

        $this->assertAuthenticated();

        $response->assertRedirect(route('profile.edit'));
    }
}
