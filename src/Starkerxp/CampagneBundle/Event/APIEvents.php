<?php
/**
 * Created by PhpStorm.
 * User: DIEU
 * Date: 29/09/2016
 * Time: 02:12
 */

namespace Starkerxp\CampagneBundle\Event;


class APIEvents
{
    const EVENT_OPEN = 'open';
    const EVENT_CLICK = 'click';
    const EVENT_SOFT_BOUNCE = 'bounce';
    const EVENT_HARD_BOUNCE = 'hardbounce';
    const EVENT_SPAM = 'spam';
    const EVENT_BLOCKED = 'blocked';
    const EVENT_UNSUB = 'unsubcribe';
}