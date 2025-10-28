<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Place;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{
    // Show attendance page
    public function index()
    {
        $user = auth()->user();

        // Fetch all attendances for this user, ordered by date descending
        $attendances = Attendance::where('user_id', $user->id)
                                 ->orderBy('date', 'desc')
                                 ->orderBy('check_in', 'asc')
                                 ->get();

        return view('attendance.index', [
            'user' => $user,
            'attendances' => $attendances,
        ]);
    }

    // Check-In
    public function checkIn(Request $request)
    {
        $user = auth()->user();

        $request->validate([
            'place_id' => 'required|exists:places,id',
        ]);

        // Check if the user has an active attendance (not checked out)
        $activeAttendance = Attendance::where('user_id', $user->id)
                                      ->whereNull('check_out')
                                      ->first();

        if ($activeAttendance) {
            return redirect()->route('attendance.index')
                             ->with('error', 'You must check out from your current attendance before checking in again.');
        }

        // Create new attendance record
        $attendance = Attendance::create([
            'user_id' => $user->id,
            'place_id' => $request->place_id,
            'date' => today(),
            'check_in' => now(),
        ]);

        return redirect()->route('attendance.index')
                         ->with('success', 'Checked in at ' . $attendance->check_in->format('H:i') 
                               . ' at place ' . $attendance->place->residence . ' - Block ' . $attendance->place->block);
    }

    // Check-Out
    public function checkOut(Request $request, Attendance $attendance)
    {
        if (!$attendance->check_out) {
            $attendance->check_out = now();
            $attendance->save();
        }

        return redirect()->back()->with('success', 'Checked out successfully!');
    }

    // Delete attendance
    public function destroy($id)
    {
        Attendance::destroy($id);
        return redirect()->route('attendance.index')
                         ->with('success', 'Attendance deleted successfully!');
    }
}
