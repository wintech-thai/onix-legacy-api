#!/bin/bash

API_ENDPOINT=https://onix-legacy-api.acd-np.its-software-services.com/onix/api/acd/acdesign/dispatcher.php
DATA_FILE=data/echo.xml
DATA_CONTENT=$(cat ${DATA_FILE})

curl -v -k -X POST -H "Content-Type: application/x-www-form-urlencoded" \
-d "DBOSOBJ=${DATA_CONTENT}" \
${API_ENDPOINT}
