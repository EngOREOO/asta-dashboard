<?php

namespace App\Jobs;

use App\Models\Course;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessNewCourse implements ShouldQueue
{
    use InteractsWithQueue, Queueable, SerializesModels;

    protected $courseId;
    protected $thumbnail;

    public function __construct($courseId, $thumbnail)
    {
        $this->courseId = $courseId;
        $this->thumbnail = $thumbnail;
    }

    public function handle(): void
    {
        if ($this->thumbnail) {
            $path = $this->thumbnail->store('course-thumbnails', 'public');
            Course::where('id', $this->courseId)->update(['thumbnail' => $path]);
        }
    }
}
