#!/bin/bash

. ./export-dev.bash

API_ENDPOINT=https://onix-legacy-api.acd-np.its-software-services.com/onix/api/acd/acdesign/dispatcher.php
DATA_FILE=data/GetCompanyProfileInfo.xml
DATA_CONTENT=$(cat ${DATA_FILE})

curl -s -k -X POST -H "Content-Type: application/x-www-form-urlencoded" \
-d "DBOSOBJ=${DATA_CONTENT}" \
-u ${BASIC_AUTH_USER}:${BASIC_AUTH_PASSWORD} \
${API_ENDPOINT}
