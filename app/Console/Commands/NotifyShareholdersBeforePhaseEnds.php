<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\PhaseEndingReminder;
use Webkul\MUMBOS\Models\Contribution;
use Symfony\Component\Console\Input\InputOption;

class NotifyShareholdersBeforePhaseEnds extends Command
{
    protected $signature = 'app:notify-shareholders-before-phase-ends';
    protected $description = 'Send reminders to shareholders before a contribution phase closes.';

    public function handle()
    {
        $days = [7, 2, 1];
        $now = Carbon::now()->startOfDay();

        foreach ($days as $d) {
            $targetDate = $now->copy()->addDays($d);

            $contributions = Contribution::with('shareholder.customer', 'phase')
                ->whereHas('phase', function ($query) use ($targetDate) {
                    $query->whereDate('ends_at', $targetDate);
                })
                ->approved()
                ->get();

            $grouped = $contributions->groupBy(function ($c) {
                return $c->shareholder_id . '-' . $c->phase_id;
            });

//             foreach ($grouped as $group) {
//     $contribution = $group->first();
//     $customer = $contribution->shareholder->customer ?? null;
//     if ($customer && $customer->email) {
//         Mail::to($customer->email)->queue(
//             new PhaseEndingReminder($contribution->phase, 7, $contribution)
//         );
//         $this->info("Test email sent to {$customer->email}");
//     }
// }

            foreach ($grouped as $group) {
                $contribution = $group->first();
                $shareholderId = $contribution->shareholder_id;
                $phaseId = $contribution->phase_id;

                $alreadySent = DB::table('phase_notifications')
                    ->where('shareholder_id', $shareholderId)
                    ->where('phase_id', $phaseId)
                    ->where('days_left', $d)
                    ->where('phase_end_date', $contribution->phase->ends_at->toDateString())
                    ->exists();

                if ($alreadySent) {
                    continue;
                }

                $customer = $contribution->shareholder->customer ?? null;

                if ($customer && $customer->email) {
                    Mail::to($customer->email)->queue(
                        new PhaseEndingReminder($contribution->phase, $d, $contribution)
                    );

                    DB::table('phase_notifications')->insert([
                        'shareholder_id' => $shareholderId,
                        'phase_id' => $phaseId,
                        'days_left' => $d,
                        'phase_end_date' => $contribution->phase->ends_at->toDateString(),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
            }

            $this->info("Checked notifications for phases ending in $d day(s).");
        }

        return Command::SUCCESS;
    }

    protected function getOptions()
    {
        return [
            ['force', null, InputOption::VALUE_NONE, 'Force the operation to run when in maintenance mode.'],
            ['quiet', null, InputOption::VALUE_NONE, 'Do not output any message.'],
            ['verbose', null, InputOption::VALUE_NONE, 'Output more verbose messages.'],
            ['version', null, InputOption::VALUE_NONE, 'Output the version and exit.'],
            ['help', null, InputOption::VALUE_NONE, 'Display this help message.'],
        ];
    }
}
