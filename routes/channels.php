<?php

use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the broadcast channels that your
| application supports. The required channels are declared here
| so that they may be registered with the broadcasting service
| providers within your application's bootstrap file.
|
*/

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

// Public channels
Broadcast::channel('notifications', function ($user) {
    return true;
});

Broadcast::channel('villa.{villaId}', function ($user, $villaId) {
    return true;
});

Broadcast::channel('chat.{conversationId}', function ($user, $conversationId) {
    return true;
});

Broadcast::channel('admin.notifications', function ($user) {
    return $user->is_admin ?? false;
});

// Order channels
Broadcast::channel('admin.orders', function ($user) {
    return $user->is_admin ?? false;
});

Broadcast::channel('order.{orderId}', function ($user, $orderId) {
    return true;
});
