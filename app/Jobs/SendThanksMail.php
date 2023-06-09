<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Mail\ThanksMail;

class SendThanksMail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $products;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($products,$user)
    {
        $this->products=$products;
        $this->user=$user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // sleep(10);
        // Mail::to('test@example.com')
        // ->send(new TestMail());
        // Mail::to()の引数に入れた連想配列(オブジェクト)の中にemailキーがあれば自動的にそれを送り先として取得してくれる。
        Mail::to($this->user)
        ->send(new ThanksMail($this->products,$this->user));
    }
}
