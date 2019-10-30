<?php

// put every settings file on /config in a single variable
$files = glob('../config/*.php', GLOB_BRACE);
$settings = [];
foreach ($files as $file) {
$settings += require $file;
}

// remove index 'settings'
return $settings['settings'];