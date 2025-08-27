<?php

namespace App\Events;

use App\Models\TestSession;
use App\Models\TestResult;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TestCompleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public TestSession $session;
    public TestResult $result;

    /**
     * Create a new event instance.
     */
    public function __construct(TestSession $session, TestResult $result)
    {
        $this->session = $session;
        $this->result = $result;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('test-completed.' . $this->session->user_id),
        ];
    }
}
