<?php

// Config.php sets some variables
// Replace with your own data and save as config.php
//

return (object) array(
// Bibliogram settings
// There are several instances that offer a feed.
// Some are blocked from time to time so I have two here and can switch between them

// 'feedUrl' => 'https://bibliogram.kavin.rocks/u/YOURFEEDNAME/rss.xml',
'feedUrl' => 'https://bibliogram.ggc-project.de/u/YOURFEEDNAME/rss.xml',

// WithKnown settings
'action' => "/micropub/endpoint",
'endpoint' => 'https://YOUR.KNOWN.INSTALL/micropub/endpoint', //Add your domain

'username' => 'YOUR KNOWN USERNAME', // Find these at YOURDOMAIN/admin/apitester
'known_api_key' => "YOUR API KEY",   // and past in here
);
