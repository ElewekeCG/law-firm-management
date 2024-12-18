<?php
namespace App\Http\View\Composers;

use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class NotificationComposer
{
    /**
     * Bind data to the view.
     *
     * @param  View  $view
     * @return void
     */
    public function compose(View $view)
    {
        $user = Auth::user();
        $unreadNotifications = $user ? $user->unreadNotifications()->latest()->limit(5)->get() : collect();

        $view->with('unreadNotifications', $unreadNotifications);
    }
}
