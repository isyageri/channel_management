 while :
   do
   clear

   tput cup 23 1
   echo "                              Copyright (c) 2013 ISCS"
   echo "                             Information System CENTER"
   tput cup 1 1
   echo "                                                                    "
   echo "          +---------------------------------------------------------+"
   echo "          |######### <|  lihat Log proses Menu 1           |> ######|"
   echo "          +---------------------------------------------------------+"
   tput cup 19 0
   echo "{ exit?, enter YYYYMM = q } "

   tput cup 10 0
   echo    "input Bultag YYYYMM:"


   tput cup 10 21
   echo -n "  "
   read thnbln;
   case $thnbln in
   q) exit;;
   x);;
   esac
   #clear
   
      
NAME="menu_1_2_1"
LOCK="./lock/${NAME}_$thnbln.lock"
out="./log/${NAME}_$thnbln.log"

#-- Error Function:
error () {
echo "$1" 1>&2
exit 1
}
if [ -f "$out" ];then
   more $out
else
  echo "Sudah terhapus/file tidak ada"
 fi


#clear
   echo " "
   echo -n "Tekan Enter untuk kembali ke menu sebelumnya ... "
   read tekan
done