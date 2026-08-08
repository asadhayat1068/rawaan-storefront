<?php

namespace Fleetbase\Storefront\Listeners;

use Fleetbase\FleetOps\Events\OrderDriverAssigned;
use Fleetbase\FleetOps\Models\Contact;
use Fleetbase\FleetOps\Models\Order;
use Fleetbase\Storefront\Notifications\StorefrontOrderDriverAssigned;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleOrderDriverAssigned implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     *
     * @param object $event
     *
     * @return void
     */
    public function handle(OrderDriverAssigned $event)
    {
        /** @var Order $order */
        $order = $event->getModelRecord();

        // halt if unable to resolve order record from event
        if (!$order instanceof Order) {
            return;
        }

        // if storefront order notify customer driver has been assigned
        if ($order->hasMeta('storefront_id')) {
            $customer = Contact::withoutGlobalScopes()->find($order->customer_uuid);
            $customer->notify(new StorefrontOrderDriverAssigned($order));
        }
    }
}

