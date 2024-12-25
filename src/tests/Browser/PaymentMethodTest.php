<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\DuskTestCase;
use App\Models\User;
use App\Models\Item;
use App\Models\Category;

class PaymentMethodTest extends DuskTestCase
{
public function testPaymentMethodIsDisplayedImmediately()
    {
        $this->browse(function (Browser $browser) {
            // テスト用ユーザーでログイン
            $user = User::factory()->create();

            $browser->loginAs($user)
                ->visit('/purchase') // 対象ページのURL
                ->assertSee('支払い方法') // ページが正しく表示されているか確認
                ->select('#payment-method', 'konbini') // コンビニ払いを選択
                ->pause(500) // JavaScriptの反映を待つ
                ->assertSeeIn('#payment-method-display', 'コンビニ払い') // 確認画面に反映されているか
                ->select('#payment-method', 'card') // カード支払いを選択
                ->pause(500)
                ->assertSeeIn('#payment-method-display', 'カード支払い'); // 確認画面に反映されているか
        });
    }
    }
/* {
    use RefreshDatabase;

    private $user;
    private $item;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $this->user = User::factory()->create();
        $this->seed(\Database\Seeders\ItemsTableSeeder::class);
        $item = Item::first();
    }

    public function test_can_select_payment_method()
    {
        $response = $this->actingAs($this->user);
        $response = $this->get(route('purchase.index',['item_id' => $item->id]));
        $response->assertStatus(200);
    }
} */
