#!/bin/bash

cd /wis/system/bin
cat patch.xml | php dispatcher.php
cd -

# Start apache2
docker-entrypoint apache2-foreground
