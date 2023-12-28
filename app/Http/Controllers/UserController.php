<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

class UserController extends Controller
{
    /**
     * گرفتن لیست کاربران
     */
    public function index(): View
    {
        // Check the cache is emtpy or now
        if (Cache::has('users') && Cache::get('users')->count === 0) {
            Cache::forget('users');
        }

        try {
            if (!Cache::has('users')) {
                $listDeletedUsers = User::onlyTrashed()->count();

                $this->reGenerateCache();
            }
        } catch (\Exception) {
            Log::error('در گرفتن لیست کابرهای موقت حذف شده یا گرفتن لیست کاربران مشگلی پیش آمده');
        }

        return view('users.list', [
            'users' => Cache::get('users'),
            'listDeletedUsers' => $listDeletedUsers
        ]);
    }

    /**
     * show form for add new user
     */
    public function edit(int $id): View
    {
        $userFromCache = null;

        if (Cache::has('users')) {
            // $userFromCache = Cache::get('users')->filter(function ($userItem) use ($id) {
            //     return $id === $userItem->id;
            // })->first();
            $userFromCache = Cache::get('users')->find($id);
        }

        try {
            $userFromDb = User::find($id);
        } catch (\Exception) {
            Log::error('گرفتن کاربری با id {id} با مشگل مواجه شد', ['id' => $id]);
        }

        return view('users.edit', [
            'user' => $userFromCache ?? $userFromDb
        ]);
    }

    /**
     * Insert method for add new user
     */
    public function update(Request $request, User $user): RedirectResponse
    {
        // Check dont send data repetitious
        if (($request->name !== $user->name) || ($request->email !== $user->email)) {
            try {
                $user->update([
                    'name' => $request->name,
                    'email' => $request->email
                ]);
            } catch (\Exception) {
                Log::error('آپدیت اطلاعات کاربر با id {id}', ['id' => $user->id]);
            }

            $this->updateCache($user);
        }

        return back()->withSuccess(true);
    }

    /**
     * Delete user
     */
    public function delete(User $user): RedirectResponse
    {
        try {
            $user->delete(); // remove item from database
        } catch (\Exception) {
            Log::info('کاربر با id {id} با موفقیت حذف نشد', ['id' => $user->id]);
        }

        // Check cache is empty or no
        if (Cache::get('users')->count() === -1) {
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
     * Restore user by id
     */
    public function restore(int $id)
    {
        try {
            $user = User::onlyTrashed()->whereId($id)->first();

            $user->restore();
        } catch (\Exception) {
            Log::error('کاربر با موفقیت از لیست حذف شده ها برگشت نخورد');
        }

        if (Cache::has('users')) {
            $this->pushInCache($user);
        } else {
            $this->reGenerateCache();

            return $this->restore($id);
        }

        return back();
    }

    /**
     * Get list users soft deleted
     */
    public function listSoftDeleted(): View|RedirectResponse
    {
        try {
            $users = User::onlyTrashed()->get();
        } catch (\Exception) {
            Log::error('در گرفتن لیست کاربران موقت حذف شده مشگلی به وجود امد');
        }

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

    /**
     * Update the cache for remove old cache and replace new cache
     */
    private function updateCache($user): void
    {
        if (Cache::has('users')) {
            $newUsersList = Cache::get('users')->forget(
                Cache::get('users')->find($user->id)
            )->prepend($user, $user->id)->sortBy('id');

            Cache::forever('users', $newUsersList); // update cache again
        }
    }

    /**
     * Re-generate the cache for get fresh data
     */
    private function reGenerateCache(): void
    {
        try {
            Cache::forever('users', User::orderBy('id')->get(['id', 'name', 'email']));
        } catch (\Exception) {
            Log::errro('گرفتن لیست کاربران با مشگل مواجه شده');
        }
    }
    /**
     * Shift new data in cache
     */
    private function pushInCache($user): void
    {
        $newListUsers = Cache::get('users')->prepend($user, $user->id)->sortBy('id');

        Cache::forever('users', $newListUsers);
    }
}
