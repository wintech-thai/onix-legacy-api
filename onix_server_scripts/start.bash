#!/bin/bash

cd /wis/system/bin
cat patch.xml | php dispatcher.php

apache2 -D FOREGROUND
