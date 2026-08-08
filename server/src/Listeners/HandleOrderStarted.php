<?php

namespace Fleetbase\Storefront\Listeners;

use Fleetbase\FleetOps\Events\OrderStarted;
use Fleetbase\FleetOps\Models\Contact;
use Fleetbase\Storefront\Notifications\StorefrontOrderEnroute;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandleOrderStarted implements ShouldQueue
{
    use InteractsWithQueue;
    /**
     * Handle the event.
     *
     * @param object $event
     *
     * @return void
     */
    public function handle(OrderStarted $event)
    {
        /** @var \Fleetbase\FleetOps\Models\Order $order */
        $order = $event->getModelRecord();

        // if storefront order / notify customer driver has started and is en-route
        if ($order->hasMeta('storefront_id')) {
            $customer = Contact::withoutGlobalScopes()->find($order->customer_uuid);
            $customer->notify(new StorefrontOrderEnroute($order));
        }
    }
}

