<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\Item;
use App\Models\Category;
use App\Models\User;
use App\Models\Favorite;

class FavoriteTest extends TestCase
{
    use RefreshDatabase;

    private $user;
    private $items;
    private $likedItems;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\CategoriesTableSeeder::class);
        $this->user = User::factory()->create();
        $this->seed(\Database\Seeders\ItemsTableSeeder::class);
        $this->items = Item::take(5)->get();
    }

    public function test_a_user_can_like_an_item()
    {
        $this->actingAs($this->user);
        $item = $this->items->first();
        $initialCount = $item->favorites()->count();
        $response = $this->post(route('item.toggleLike', $item->id));
        $response->assertStatus(200);

        $this->assertDatabaseHas('favorites', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
        ]);
        $this->assertEquals($initialCount + 1, $item->fresh()->favorites()->count());
    }

    public function test_favorite_icon_shows_filled_star_when_item_is_liked()
    {
        $this->actingAs($this->user);
        $item = $this->items->first();
        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $item->id
        ]);
        $response = $this->get(route('item.show', $item->id));
        $response->assertSee('fas fa-star liked', false);
        $response->assertDontSee('far fa-star', false);
    }

    public function test_a_user_can_unlike_an_item()
    {
        $this->actingAs($this->user);
        $item = $this->items->first();

        Favorite::create([
            'user_id' => $this->user->id,
            'item_id' => $item->id
        ]);

        $initialCount = $item->favorites()->count();
        $response = $this->post(route('item.toggleLike', $item->id));
        $response->assertStatus(200);

        $this->assertDatabaseMissing('favorites', [
            'user_id' => $this->user->id,
            'item_id' => $item->id,
        ]);
        $this->assertEquals($initialCount - 1, $item->fresh()->favorites()->count());
    }
}
