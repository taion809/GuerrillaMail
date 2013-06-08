<?php

namespace GuerrillaMail\GuerrillaConnect;

interface GuerrillaConnectInterface
{
    public function retrieve($query);
    public function transmit($query);
}
