<?php

namespace App\Jobs;

use App\Domains\Post\Models\Post;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Carbon\Carbon;

class SchedulerJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected $post;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        if ($this->post->status === Post::STATUS_SCHEDULE) {
            if ($this->post->published_at && Carbon::parse($this->post->published_at)->isToday()) {
                $this->post->status = Post::STATUS_PUBLISH;
                if (!$this->post->published_at) {
                    $this->post->published_at = now();
                }
                $this->post->save();
            }
        }
    }
}
