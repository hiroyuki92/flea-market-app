<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'item_id',
        'shipping_postal_code',
        'shipping_address_line',
        'shipping_building',
        'payment_method'];

    // ユーザーとのリレーション
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // 商品とのリレーション
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
