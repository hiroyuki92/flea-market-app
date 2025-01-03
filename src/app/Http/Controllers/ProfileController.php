<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\ProfileRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function show(Request $request)
    {
        $tab = $request->input('tab', 'sell');
        $user = Auth::user();

        // データの取得
        $items = $tab === 'sell' ? $user->items : [];
        $purchases = $tab === 'buy' ? $user->purchases()->with('item')->get() : [];

        return view('profile', compact('purchases', 'items', 'tab'));
    }

    /**
     * ユーザーのプロフィール編集フォームを表示
     *
     * @return \Illuminate\View\View
     */
    public function edit()
    {
        $user = Auth::user();

        return view('edit', compact('user'));
    }

    /**
     * ユーザーのプロフィールを更新
     *
     * @param  \App\Http\Requests\ProfileRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(ProfileRequest $request)
    {
        $user = Auth::user();

        // 画像がアップロードされている場合
        if ($request->hasFile('profile_image')) {
            // 古い画像を削除（もし存在すれば）
            if ($user->profile_image) {
                Storage::delete('public/profile_images/' . $user->profile_image);
            }

            // 新しい画像を保存
            $imagePath = $request->file('profile_image')->store('profile_images', 'public');

            // プロフィール画像のパスを保存
            $user->profile_image = basename($imagePath);
        }

        $user->name = $request->name;
        $user->postal_code = $request->postal_code;
        $user->address_line = $request->address_line;
        $user->building = $request->building;

        $user->save();

        return redirect()->route('profile.show');
    }
}
