#!/bin/bash

cd /wis/system/bin
cat patch.xml | php dispatcher.php
cd -

# Start apache2
docker-php-entrypoint apache2-foreground
