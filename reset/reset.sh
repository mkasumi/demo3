#!/bin/sh

ROOT=/Applications/MAMP/htdocs/demo3/
MYSQL_PATH=/Applications/MAMP/Library/bin/
LOGFILE=${ROOT}reset/log

DB_MASTER_NAME=DBdemo3
DB_NAME=DBdemo3_sandbox
DB_USER=root
DB_PASS=root

cd ${ROOT}

{
${MYSQL_PATH}mysql --user=${DB_USER} --password=${DB_PASS} <<EOF
    DROP DATABASE ${DB_NAME};
    CREATE DATABASE ${DB_NAME};
    exit
EOF
​
${MYSQL_PATH}mysqldump --user=${DB_USER} --password=${DB_PASS} ${DB_MASTER_NAME} > ${ROOT}reset/temp.dump
${MYSQL_PATH}mysql --user=${DB_USER} --password=${DB_PASS} ${DB_NAME} < ${ROOT}reset/temp.dump
​
rm -rf ${ROOT}archives
cp -R ${ROOT}archives_master ${ROOT}archives

rm -rf ${ROOT}themes
cp -R ${ROOT}themes_master ${ROOT}themes
​
} >> "$LOGFILE" 2>&1