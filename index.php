<?php

include('vendor/autoload.php');

var_dump(explode("\n", wordwrap("Wednesday                 is hump                    day, but has anyone\n asked the camel if he’s happy about it? ", 15, "\n", true)));
