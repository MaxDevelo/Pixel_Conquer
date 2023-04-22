<?php
use App\Models\Pixel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UpdateCarte
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $pixel;

    public function __construct(Pixel $pixel)
    {
        $this->pixel = $pixel;
        
    }

    public function broadcastOn()
    {
        return ['pixel-channel'];
    }

    public function broadcastAs()
    {
        return 'PixelUpdated';
    }
}
