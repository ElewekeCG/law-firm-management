<?php

namespace App\Http\Controllers;

use App\Models\Appointments;
use App\Models\Cases;
use App\Models\proceedings;
use Carbon\Carbon;
// use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Dashboard extends Controller
{
    public function showDashboard()
    {
        $user = auth()->user();
        $now = Carbon::now();
        $notifications = Auth::user()->notifications()->paginate(5);

        $newAppointments = Appointments::where(function($query) use ($user) {
            if ($user->isClient()) {
                $query->where('clientId', $user->id);
            } elseif ($user->isLawyer()) {
                $query->where('lawyerId', $user->id);
            }
        })
        ->where('startTime', '>', $now)
        ->where('startTime', '<', $now->copy()->addDays(7))
        ->where('status', '=', 'scheduled')
        ->count();

        $upcomingCases = Appointments::where(function($query) use ($user) {
            if ($user->isClient()) {
                $query->where('clientId', $user->id);
            } elseif ($user->isLawyer()) {
                $query->where('lawyerId', $user->id);
            }
        })
        ->where('startTime', '>', $now)
        ->where('startTime', '<', $now->copy()->addDays(7))
        ->where('type', '=', 'court_appearance')
        ->where('status', '!=', 'cancelled')
        ->count();

        $pendingDocs = proceedings::where(function($query) use ($user){
            if ($user->isLawyer() || $user->isClerk) {
                $query;
            }
        })
        ->where('dueDate', '>', $now)
        ->where('dueDate', '<', $now->copy()->addDays(14))
        ->where('docStatus', '=', 'pending')
        ->count();

        return view('Dashboard', compact([
            'user' => 'user',
            'newAppointments' => 'newAppointments',
            'upcomingCases' => 'upcomingCases',
            'pendingDocs' => 'pendingDocs',
            'notifications' => 'notifications'
        ]
        ));
    }

    public function getAppointments()
    {
        $user = auth()->user();
        $now = Carbon::now();

        $appointments = Appointments::where(function($query) use ($user) {
                if ($user->isClient()) {
                    $query->where('clientId', $user->id);
                } elseif ($user->isLawyer()) {
                    $query->where('lawyerId', $user->id);
                }
            })
            ->where('startTime', '>', $now)
            ->where('startTime', '<', $now->copy()->addDays(7))
            ->where('status', '=', 'scheduled')
            ->orderBy('startTime')
            ->paginate(10);
            // ->get();

        return view('appointments.upcoming', compact('appointments', 'user'));
    }

    public function getUpcomingCases()
    {
        $user = auth()->user();
        $now = Carbon::now();
        $cases = Cases::all();

        $upcomingCases = Appointments::where(function($query) use ($user) {
            if ($user->isClient()) {
                $query->where('clientId', $user->id);
            } elseif ($user->isLawyer()) {
                $query->where('lawyerId', $user->id);
            } elseif ($user->isClerk()) {
                $query;
            }
        })
        ->where('startTime', '>', $now)
        ->where('startTime', '<', $now->copy()->addDays(7))
        ->where('status', '!=', 'cancelled')
        ->where('type', '=', 'court_appearance')
        ->orderBy('startTime')
        ->paginate(10);

        return view('cases.upcoming', compact(
            'user',
            'upcomingCases'
        ));
    }

    public function getPendingDocs()
    {
        $user = auth()->user();
        $now = Carbon::now();

        $pendingDocs = proceedings::where(function($query) use ($user){
            if ($user->isLawyer() || $user->isClerk) {
                $query;
            }
        })
        ->where('dueDate', '>', $now)
        ->where('dueDate', '<', $now->copy()->addDays(14))
        ->where('docStatus', '=', 'pending')
        ->paginate(10);

        return view('cases.upcomingPro', compact([
            'user' => 'user',
            'pendingDocs' => 'pendingDocs'
        ]
        ));
    }
}

