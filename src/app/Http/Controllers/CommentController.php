<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Item;
use Illuminate\Http\Request;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(CommentRequest $request, $itemId)
    {
        Comment::create([
            'item_id' => $itemId,  // コメント対象のアイテムID
            'user_id' => Auth::id(),  // 現在ログインしているユーザーのID
            'content' => $request->comment,  // コメント内容
        ]);

        return redirect()->route('item.show', ['item_id' => $itemId]);
    }

}