#!/bin/bash

# init_script.bash '/onix/dev/wtt/development/dispatcher.php' username password

export ONIX_DOCKER_MODE='true'
export REQUEST_URI=$1

USER_NAME=$2
USER_PASSWD=$3

PATCH_FILE=/tmp/patch.xml
CREATE_USER_FILE=/tmp/create_user.xml

cat << EOF > ${PATCH_FILE}
<?xml version="1.0" encoding="UTF-8"?>
<API>
  <OBJECT name="PARAM">
    <FIELD name="FUNCTION_NAME">Patch</FIELD>
    <FIELD name="SESSION"></FIELD>
    <FIELD name="LOGIN_ID"></FIELD>
    <FIELD name="DEBUG_FLAG">N</FIELD>
    <FIELD name="WisWsClientAPI_VERSION">1.0.5</FIELD>
    <FIELD name="SCREEN_ID">Onix.ClientCenter.WinLogin</FIELD>
  </OBJECT>
  <OBJECT name="DUMMY" />
</API>
EOF

cat << EOF > ${CREATE_USER_FILE}
<?xml version="1.0" encoding="UTF-8"?>
<API>
  <OBJECT name="PARAM">
    <FIELD name="FUNCTION_NAME">CreateInitAdminUser</FIELD>
    <FIELD name="SESSION"></FIELD>
    <FIELD name="LOGIN_ID"></FIELD>
    <FIELD name="DEBUG_FLAG">N</FIELD>
    <FIELD name="WisWsClientAPI_VERSION">1.0.5</FIELD>
    <FIELD name="SCREEN_ID">Onix.ClientCenter.WinLogin</FIELD>
  </OBJECT>
  <OBJECT name="DUMMY">
    <FIELD name="USER_NAME">${USER_NAME}</FIELD>
    <FIELD name="PASSWORD">${USER_PASSWD}</FIELD>
    <FIELD name="IS_ADMIN">Y</FIELD>
    <FIELD name="IS_ENABLE">Y</FIELD>
    <FIELD name="DESCRIPTION">Initial user created by the system</FIELD>
  </OBJECT>
</API>
EOF

cd /wis/system/bin
php ./dispatcher.php -if ${PATCH_FILE}
php ./dispatcher.php -if ${CREATE_USER_FILE}
cd -
