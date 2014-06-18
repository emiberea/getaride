<?php

namespace EB\CommunicationBundle\Event;

final class NotificationEvents
{
    // FRIEND_REQUEST
    const FRIEND_REQUEST_SENT     = 'notification.friend_request.sent';
    const FRIEND_REQUEST_ACCEPTED = 'notification.friend_request.accepted';
    const FRIEND_REQUEST_REJECTED = 'notification.friend_request.rejected';
    // RIDE_REQUEST
    const RIDE_REQUEST_SENT     = 'notification.ride_request.sent';
    const RIDE_REQUEST_ACCEPTED = 'notification.ride_request.accepted';
    // RIDE
    const RIDE_CANCELED = 'notification.ride.canceled';
    const RIDE_CLOSED   = 'notification.ride.closed';
    // RATING
    const RATING_AWARDED = 'notification.rating.awarded';
}
