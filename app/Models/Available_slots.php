<?php

namespace App\Models;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Available_slots extends Model
{
    use HasFactory;

    protected $fillable = [
        'lawyerId',
        'startTime',
        'endTime',
        'status'
    ];

    protected $dates = ['startTime', 'endTime'];

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyerId');
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    // generate slots
    public static function generateSlots($lawyerId, Carbon $date)
    {
        // set business hours
        $startTime = $date->copy()->setTime(8, 0);
        $endTime = $date->copy()->setTime(17, 0);
        $slotDuration = 30;

        $slots = collect();
        // $current = $startTime->copy();

        while ($startTime < $endTime) {
            $slotStart = $startTime->copy();
            $slotEnd = $startTime->copy()->addMinutes($slotDuration);

            $existigSlot = static::where('lawyerId', $lawyerId)
                ->where('startTime', $slotStart)
                ->where('endTime', $slotEnd)
                ->exists();

            if (!$existigSlot) {
                $slots->push(static::create([
                    'lawyerId' => $lawyerId,
                    'startTime' => $slotStart,
                    'endTime' => $slotEnd,
                    'status' => 'available'
                ]));

            }

            $startTime->addMinutes($slotDuration);
        }
        return $slots;
    }

    // mark slot as booked
    public function book()
    {
        $this->status = 'booked';
        $this->save();
    }

}
