#!/bin/bash

API_ENDPOINT=https://onix-legacy-api.acd-np.its-software-services.com/onix/api/acd/acdesign/dispatcher.php
DATA_FILE=data/echo.xml

curl -v -k -F "DBOSOBJ=@${DATA_FILE}" ${API_ENDPOINT}
