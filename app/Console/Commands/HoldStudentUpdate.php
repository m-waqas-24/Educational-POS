<?php

namespace App\Console\Commands;

use App\Mail\UnconfirmedStudentMail;
use App\Models\StudentCourse;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class HoldStudentUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'hold:student';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check hold student confirm or not daily';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $studentCourses24 = StudentCourse::where('is_confirmed', 0)
        ->where('updated_at', '<=', now()->subHours(24))
        ->get();

        $studentCourses48 = StudentCourse::where('is_confirmed', 0)
        ->where('updated_at', '<=', now()->subHours(48))
        ->get();

        if ($studentCourses24->count() > 0) {
        Mail::to('ahmdshrkhnn@gmail.com')->send(new UnconfirmedStudentMail($studentCourses24));
        }

        if ($studentCourses48->count() > 0) {
        Mail::to('secondemail@example.com')->send(new UnconfirmedStudentMail($studentCourses48));
        }

    }
}
