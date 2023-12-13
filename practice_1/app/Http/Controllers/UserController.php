<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * گرفتن لیست کاربران
     */
    public function index()
    {
        if (Cache::has('users') && Cache::get('users')->count() === 0) {
            Cache::forget('users');
        }

        if (!Cache::has('users')) {
            Cache::forever('users', User::orderBy('id')->get(['id', 'name', 'email']));
        }

        return view('users.list', [
            'users' => Cache::get('users'),
            'listDeletedUsers' => User::onlyTrashed()->count()
        ]);
    }

    /**
     * Delete user
     */
    public function delete(User $user)
    {
        $user->delete(); // remove item from database

        if (Cache::get('users')->count() === 0) {
            Cache::forget('users');

            return to_route('home');
        }

        $newListUsersCache = Cache::get('users')->filter(function ($item) use ($user) {
            return $item->id != $user->id;
        }); // Delete old item from cache

        Cache::forever('users', $newListUsersCache); // replace new cache with old cache

        return back()->withSuccess(true);
    }

    /**
     * show form for add new user
     */
    public function edit(int $id): View
    {
        $user = null;

        if (Cache::has('users')) {
            $user = Cache::get('users')->filter(function ($userItem) use ($id) {
                return $id === $userItem->id;
            })->first();
        }

        return view('users.edit', [
            'user' => $user ?? User::find($id)
        ]);
    }

    /**
     * Insert method for add new user
     */
    public function update(Request $request, User $user)
    {
        if (($request->name !== $user->name) || ($request->email !== $user->email)) {
            $user->update([
                'name' => $request->name,
                'email' => $request->email
            ]);

            $this->updateCache($user);
        }

        return back()->withSuccess(true);
    }

    /**
     * Get list users soft deleted
     */
    public function listSoftDeleted(): View|RedirectResponse
    {
        $users = User::onlyTrashed()->get();

        if ($users->count() === 0) {
            return to_route('home');
        }

        return view('users.list-softDeleted', compact('users'));
    }

    /**
     * Force delete user by id
     */
    public function forceDelete(int $id): RedirectResponse
    {
        User::onlyTrashed()->whereId($id)->forceDelete();

        if (!User::onlyTrashed()->count()) {
            return to_route('home');
        }

        return back();
    }

    private function updateCache($user): void
    {
        if (Cache::has('users')) {
            $newUsersList = Cache::get('users')->reject(function ($item) use ($user) {
                return $item->id === $user->id;
            })->prepend($user, $user->id)->sortBy('id');

            Cache::forever('users', $newUsersList);
        }
    }
}
