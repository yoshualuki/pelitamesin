<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

abstract class OrderStatusMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(
        public $user,
        public $order
    ) {}

    abstract protected function getTemplate(): string;
    abstract protected function getSubject(): string;

    public function build()
    {
        return $this->view($this->getTemplate())
            ->subject($this->getSubject())
            ->with([
                'user' => $this->user,
                'order' => $this->order
            ]);
    }
}

class RefundDecision extends OrderStatusMail
{
    public function __construct($user, $order, public string $decision, public ?string $reason = null)
    {
        parent::__construct($user, $order);
    }

    protected function getTemplate(): string
    {
        return 'mail.user.refund-decision';
    }
    protected function getSubject(): string
    {
        return 'Refund Request ' . ucfirst($this->decision) . ' - Order #' . $this->order->id;
    }

    public function build()
    {
        return parent::build()
            ->with([
                'decision' => $this->decision,
                'reason' => $this->reason
            ]);
    }
}

// Admin Notification Classes
class AdminNewOrder extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public $order) {}

    public function build()
    {
        return $this->view('mail.admin.new-order')
            ->subject('New Order Received #' . $this->order->id)
            ->with(['order' => $this->order]);
    }
}

class AdminOrderDone extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public $order) {}

    public function build()
    {
        return $this->view('mail.admin.order-done')
            ->subject('Order Completed #' . $this->order->id);
    }
}

class AdminRefundNeedsConfirmation extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(public $order) {}

    public function build()
    {
        return $this->view('mail.admin.refund-request')
            ->subject('Refund Requires Approval #' . $this->order->id);
    }
}
