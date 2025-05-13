<?php

namespace Database\Seeders;

use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RegistrationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get all published events
        $events = Event::where('status', 'published')->get();
        $users = User::where('role', 'user')->get();
        $totalUsers = $users->count();
        
        // For each event, create registrations
        foreach ($events as $event) {
            // Determine how many registrations to create (between 10-80% of max participants or 5-30 if no max)
            $maxRegistrations = $event->max_participants ?? rand(30, 100);
            $attendancePercentage = rand(10, 80) / 100;
            $numberOfRegistrations = min(round($maxRegistrations * $attendancePercentage), $totalUsers, rand(5, 30));
            
            // Only create registrations for future or current events
            if ($event->start_date <= Carbon::now()->subDays(5)) {
                continue;
            }
            
            // Random sample of users for this event
            $eventAttendees = $users->random($numberOfRegistrations);
            
            foreach ($eventAttendees as $user) {
                // Determine registration status
                $statusChance = rand(1, 100);
                if ($statusChance <= 80) {
                    $status = 'confirmed'; // 80% are confirmed
                } elseif ($statusChance <= 95) {
                    $status = 'pending'; // 15% are pending
                } else {
                    $status = 'cancelled'; // 5% are cancelled
                }
                
                // Determine if this is a paid event registration
                $isPaid = $event->price > 0 && $status === 'confirmed';
                $ticketQuantity = rand(1, 3); // Most people register for 1-3 tickets
                $amountPaid = $isPaid ? ($event->price * $ticketQuantity) : 0;
                
                // Create payment details if paid
                $paymentMethod = null;
                $transactionId = null;
                $paymentDate = null;
                
                if ($isPaid) {
                    $paymentMethods = ['credit_card', 'paypal', 'bank_transfer', 'ideal'];
                    $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                    $transactionId = 'TRX' . strtoupper(Str::random(10));
                    $paymentDate = Carbon::now()->subDays(rand(1, 14));
                }
                
                // Create registration
                Registration::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'status' => $status,
                    'reference_code' => 'REF-' . strtoupper(Str::random(8)),
                    'ticket_quantity' => $ticketQuantity,
                    'amount_paid' => $amountPaid,
                    'payment_method' => $paymentMethod,
                    'transaction_id' => $transactionId,
                    'payment_date' => $paymentDate,
                    'is_attended' => false, // Events haven't happened yet
                    'notes' => $this->getRandomNote($status),
                    'created_at' => Carbon::now()->subDays(rand(1, 30)),
                    'updated_at' => Carbon::now(),
                ]);
            }
        }
        
        // For past events, create registrations with attendance data
        $pastEvents = Event::where('status', 'completed')->get();
        
        foreach ($pastEvents as $event) {
            // Determine how many registrations to create
            $maxRegistrations = $event->max_participants ?? rand(30, 100);
            $attendancePercentage = rand(40, 90) / 100; // Higher attendance for past events
            $numberOfRegistrations = min(round($maxRegistrations * $attendancePercentage), $totalUsers, rand(10, 40));
            
            // Random sample of users for this event
            $eventAttendees = $users->random($numberOfRegistrations);
            
            foreach ($eventAttendees as $user) {
                // Most registrations for past events are confirmed
                $status = rand(1, 100) <= 95 ? 'confirmed' : 'cancelled';
                
                // Determine if this is a paid event registration
                $isPaid = $event->price > 0 && $status === 'confirmed';
                $ticketQuantity = rand(1, 3);
                $amountPaid = $isPaid ? ($event->price * $ticketQuantity) : 0;
                
                // Create payment details if paid
                $paymentMethod = null;
                $transactionId = null;
                $paymentDate = null;
                
                if ($isPaid) {
                    $paymentMethods = ['credit_card', 'paypal', 'bank_transfer', 'ideal'];
                    $paymentMethod = $paymentMethods[array_rand($paymentMethods)];
                    $transactionId = 'TRX' . strtoupper(Str::random(10));
                    $paymentDate = Carbon::parse($event->start_date)->subDays(rand(5, 30));
                }
                
                // Determine if the user attended (for confirmed registrations)
                $isAttended = $status === 'confirmed' && rand(1, 100) <= 85; // 85% of confirmed registrations attended
                $checkInTime = $isAttended ? Carbon::parse($event->start_date)->addMinutes(rand(0, 120)) : null;
                
                // Create registration
                Registration::create([
                    'user_id' => $user->id,
                    'event_id' => $event->id,
                    'status' => $status,
                    'reference_code' => 'REF-' . strtoupper(Str::random(8)),
                    'ticket_quantity' => $ticketQuantity,
                    'amount_paid' => $amountPaid,
                    'payment_method' => $paymentMethod,
                    'transaction_id' => $transactionId,
                    'payment_date' => $paymentDate,
                    'is_attended' => $isAttended,
                    'check_in_time' => $checkInTime,
                    'notes' => $this->getRandomNote($status, $isAttended),
                    'created_at' => Carbon::parse($event->start_date)->subDays(rand(5, 30)),
                    'updated_at' => $isAttended ? $checkInTime : Carbon::parse($event->start_date)->subDays(rand(1, 5)),
                ]);
            }
        }
    }
    
    /**
     * Generate random notes for registrations
     */
    private function getRandomNote($status, $attended = null)
    {
        if ($status === 'cancelled') {
            $cancelReasons = [
                'Unable to attend due to scheduling conflict.',
                'Had to cancel due to unexpected circumstances.',
                'No longer able to attend, requested refund.',
                'Changed plans, can\'t make it anymore.',
                'Double-booked, had to cancel this event.',
            ];
            return $cancelReasons[array_rand($cancelReasons)];
        } elseif ($status === 'pending') {
            $pendingNotes = [
                'Awaiting payment confirmation.',
                'Registration pending approval.',
                'Payment processing.',
                'On waitlist, will confirm if space becomes available.',
                'Requested invoice before completing payment.',
            ];
            return $pendingNotes[array_rand($pendingNotes)];
        } elseif ($status === 'confirmed' && $attended === false) {
            $noShowNotes = [
                'No-show, did not attend event.',
                'Did not check in at the event.',
                'Confirmed but did not attend.',
                'Registered but didn\'t show up.',
                'Marked as absent from event.',
            ];
            return $noShowNotes[array_rand($noShowNotes)];
        } elseif ($status === 'confirmed' && $attended === true) {
            $attendedNotes = [
                'Checked in on time.',
                'Participated actively in event.',
                'Requested materials after event.',
                'Expressed interest in future events.',
                'Provided positive feedback.',
                'Asked about next event in series.',
                'Networked with multiple attendees.',
                'Was part of group discussion.',
            ];
            return $attendedNotes[array_rand($attendedNotes)];
        }
        
        // For confirmed registrations with no attendance info yet
        $generalNotes = [
            'Looking forward to attending.',
            'Requested vegetarian meal option.',
            'Will arrive late due to prior commitment.',
            'Needs accessibility accommodation.',
            'First time attending this type of event.',
            'Bringing colleague as second guest.',
            'Asked about parking information.',
            'Interested in networking opportunities.',
            'Requested front row seating if possible.',
            null, // Some registrations have no notes
        ];
        
        return $generalNotes[array_rand($generalNotes)];
    }
}
