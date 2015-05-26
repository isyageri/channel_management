TGL=$1
NAME="menu_1_2_1"
LOCK="./lock/${NAME}_$TGL.lock"
out="./log/${NAME}_$TGL.log"
#-- Error Function:
error () {
echo "$1" 1>&2
exit 1
}
#-------------------------
# Launch the code
#-------------------------
if [ -f "$LOCK" ]
 then
   if [ "$(ps -p `cat $LOCK` | wc -l)" -gt 1 ]; then
   #-- process is still running
    echo "Quit at start: lingering process `cat $LOCK`"
        error "Sedang proses/sudah di proses, Hub Administrator(CDM) (untuk proses ulang)!"
        exit 10
   else
   #-- process not running, but lock file not deleted
     echo "sudah di proses, Hub Administrator(CDM) (untuk proses ulang)"
   #rm $LOCK
  echo $$ >> "$LOCK"
 fi
else
  > /tmp/${NAME}.log
  echo "create lock"
  echo $$ >> "$LOCK"
  echo "done (pid=$$)"
  #echo "OK"
  sh menu_1_2_1.sh $TGL >> $out 2>&1
fi
#rm $LOCK