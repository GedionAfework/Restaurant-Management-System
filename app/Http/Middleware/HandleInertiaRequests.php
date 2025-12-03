<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Inspiring;
use Illuminate\Http\Request;
use Inertia\Middleware;
use Illuminate\Support\Facades\Auth;

class HandleInertiaRequests extends Middleware
{
    /**
     * The root template that's loaded on the first page visit.
     *
     * @see https://inertiajs.com/server-side-setup#root-template
     *
     * @var string
     */
    protected $rootView = 'app';

    /**
     * Determines the current asset version.
     *
     * @see https://inertiajs.com/asset-versioning
     */
    public function version(Request $request): ?string
    {
        return parent::version($request);
    }

    /**
     * Define the props that are shared by default.
     *
     * @see https://inertiajs.com/shared-data
     *
     * @return array<string, mixed>
     */
    public function share(Request $request): array
    {
        try {
            [$message, $author] = str(Inspiring::quotes()->random())->explode('-');
            $quote = ['message' => trim($message), 'author' => trim($author)];
        } catch (\Exception $e) {
            $quote = ['message' => 'Something inspiring', 'author' => 'Unknown'];
        }

        $user = $request->user();
        $userPermissions = null;
        
        if ($user && $user->role) {
            // Load permissions for the user's role
            $userPermissions = $user->role->permissions->pluck('slug')->toArray();
        }

        return array_merge(parent::share($request), [
            'name' => config('app.name'),
            'quote' => $quote,
            'auth' => [
                'user' => $user ? [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role ? [
                        'id' => $user->role->id,
                        'name' => $user->role->name,
                        'slug' => $user->role->slug,
                    ] : null,
                    'permissions' => $userPermissions,
                    'is_admin' => $user->isAdmin(),
                ] : null,
                'customer' => Auth::guard('customer')->user(), // Customer guard
            ],
            'flash' => [
                'success' => fn () => $request->session()->get('success'),
                'error' => fn () => $request->session()->get('error'),
            ],
        ]);
    }
}