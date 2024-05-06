<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class UnconfirmedStudentMail extends Mailable
{
    use Queueable, SerializesModels;

    public $studentCourses;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($studentCourses)
    {
        $this->studentCourses = $studentCourses;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('Unconfirmed Student Courses')
        ->view('emails.unconfirmed_students', ['studentCourses' => $this->studentCourses]);
    }
}
