<?php

namespace Wylly\Plugin_Name\Support;

use Wylly\Plugin_Name\Plugin;

function plugin_version(): string {
    return Plugin::get_instance()->get_version();
}


// use function Company\Plugin_Name\Support\get_version;  This is how to use the above function