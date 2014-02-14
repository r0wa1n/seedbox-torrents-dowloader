#!/bin/sh

# ---[ VARIABLES ]--- ##
NAME='Seedbox r0wa1n'
LFTP='/usr/local/bin/lftp'

DATE_LOG=$(date +%Y-%m-%d)

LOGS_DIR='../logs'
LOG_FILE="${LOGS_DIR}/checking-${DATE_LOG}.log"
TEMP_DIR='../temp/'

# TODO put variable in xml config file
FTP_HOST='XXX.seedbox.fr'
FTP_PORT='21'
FTP_USER='***'
FTP_PASSWORD='***'

if [ ! -d ${TEMP_DIR} ]
  then mkdir -p ${TEMP_DIR}
fi

### ---[ LOGS ]--- ##
# # Create directory log if it doesn't exists
if [ ! -d ${LOGS_DIR} ]
  then mkdir -p ${LOGS_DIR}
fi

## ---[ START LOG ]--- ##
echo "Date  : $(date +%d/%m/%Y)" >> ${LOG_FILE}
echo "Heure : $(date +%H:%M)" >> ${LOG_FILE}
echo "Checking files on server ${NAME}" >> ${LOG_FILE}

## Place in temp dir
cd ${TEMP_DIR}

CHECKING_FILE='checking_file'
CHECKING_FILE_DETAIL='checking_file_detail'
OUTPUT_JSON_FILE='seedbox_mirror_files_detail.json'

rm -rf ${OUTPUT_JSON_FILE}
touch ${CHECKING_FILE}
touch ${CHECKING_FILE_DETAIL}
touch ${OUTPUT_JSON_FILE}

## Connect ftp server
/usr/local/bin/lftp -u ${FTP_USER},${FTP_PASSWORD} ${FTP_HOST} -p ${FTP_PORT} << Eof
    nlist > ${CHECKING_FILE}
    du -a > ${CHECKING_FILE_DETAIL}
    bye
Eof

## Parse file to create many files
echo '['  >> ${OUTPUT_JSON_FILE}
cat ${CHECKING_FILE} | while read line
do
  file_size=`more ${CHECKING_FILE_DETAIL} | grep "$line" | tail -1 | awk '{print$1}'`
  echo "{\"file\": \"$line\", \"size\": $file_size}," >> ${OUTPUT_JSON_FILE}
done
echo `cat ${OUTPUT_JSON_FILE} | sed -e '$s/,$/]/'` > ${OUTPUT_JSON_FILE}

## Clean temp files
rm ${CHECKING_FILE}
rm ${CHECKING_FILE_DETAIL}

## ---[ EnD ]--- ##
exit 0
