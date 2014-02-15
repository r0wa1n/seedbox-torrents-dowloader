#!/bin/sh

#. /frontview/bin/functions

## ---[ VARIABLES ]--- ##
NAME='Seedbox r0wa1n'
LFTP='/usr/local/bin/lftp'
WGET='/usr/local/bin/wget'

DATE_LOG=$(date +%Y-%m-%d)

FILE_TO_DOWNLOAD=$1
FILES_LOGS='../logs'
LOG_FILE="${FILES_LOGS}/download-${DATE_LOG}.log"

if [ "$#" -ne 1 ]; then
    echo "[ ERROR ] Illegal number of parameters" >> ${LOG_FILE}
    echo "Illegal number of parameters"
    exit 0
fi

DOWNLOAD_DIR='../download'
TEMP_DIR='../temp'

FTP_HOST='XXX.seedbox.fr'
FTP_PORT='21'
FTP_USER='***'
FTP_PASSWORD='***'

## ---[ LOGS ]--- ##
# Create log directory if it doesn't exist
if [ ! -d ${FILES_LOGS} ]
  then mkdir -p ${FILES_LOGS}
fi

# Idem for download directory
if [ ! -d ${DOWNLOAD_DIR} ]
  then mkdir -p ${DOWNLOAD_DIR}
fi

# We going to temp dir
cd ${TEMP_DIR}

echo "[ INFO ] Downloading files from ${NAME}..." >> ${LOG_FILE}
## ---[ START LOG ]--- ##
beginDate="$(date +%d/%m/%Y) a $(date +%H:%M)"
echo "[ INFO ] Date  : $(date +%d/%m/%Y)" >> ${LOG_FILE}
echo "[ INFO ] Hour : $(date +%H:%M)" >> ${LOG_FILE}

# Download on ftp Seedbox
# TODO escape with special character like [
escapefilename="${FILE_TO_DOWNLOAD}"
echo "[ INFO ] Download $escapefilename in progress..." >> ${LOG_FILE}
echo "Download $escapefilename in progress..."
echo "do ftp://${FTP_USER}:${FTP_PASSWORD}@${FTP_HOST}/$escapefilename" >> ${LOG_FILE}
/usr/local/bin/wget -r ftp://${FTP_USER}:${FTP_PASSWORD}@${FTP_HOST}/"$escapefilename"

## TODO chown
## chown -R r0wa1n:users ${FTP_HOST}/*
#detailFile=`du -h ${FTP_HOST}/"$escapefilename"`
mv ${FTP_HOST}/"$escapefilename" ${DOWNLOAD_DIR}

echo "[ INFO ] Finish to..." >> ${LOG_FILE}
echo "[ INFO ] Finish to..."
echo "[ INFO ] Date  : $(date +%d/%m/%Y)" >> ${LOG_FILE}
echo "[ INFO ] Date  : $(date +%d/%m/%Y)"
echo "[ INFO ] Heure : $(date +%H:%M)" >> ${LOG_FILE}
echo "[ INFO ] Heure : $(date +%H:%M)"

#send email TODO
#    mesg="[$beginDate]\n\nCes fichiers viennent d'etre telecharges :\n$allfiles\n\n[$(date +%d/%m/%Y) a $(date +%H:%M)]\n\nDetail :\n\n$detail"
#    subject="Telechargement complete"
#    send_email_alert "$subject" "$mesg" "$EMAIL"

## ---[ EnD ]--- ##
exit 0
