#!/bin/bash

cd /wis/system/bin
cat patch.xml | php dispatcher.php

# Start apache2
/sbin/runit-wrapper
