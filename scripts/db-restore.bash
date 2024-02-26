#!/bin/bash

SRC_FILE=acd.db.backup.20240226
DST_FILE=/tmp/${SRC_FILE}
POD_NAME=postgresql-0
NS=onix-dev

kubectl cp ${SRC_FILE} ${NS}/${POD_NAME}:${DST_FILE}

# psql -U postgres onix_prod_acd_acdesign < acd.db.backup.20240226