<?php

namespace Fleetbase\Storefront\Listeners;

use Fleetbase\FleetOps\Events\OrderDispatched;
use Fleetbase\FleetOps\Models\Contact;
use Fleetbase\Storefront\Notifications\StorefrontOrderReadyForPickup;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleOrderDispatched implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param object $event
     *
     * @return void
     */
    public function handle(OrderDispatched $event)
    {
        /** @var \Fleetbase\FleetOps\Models\Order $order */
        $order = $event->getModelRecord();

        // notify customer order is ready for pickup
        if ($order->isMeta('is_pickup')) {
            $customer = Contact::withoutGlobalScopes()->find($order->customer_uuid);
            $customer->notify(new StorefrontOrderReadyForPickup($order));
        }
    }
}

