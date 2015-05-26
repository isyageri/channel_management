#!/bin/bash
#/******************************************************************/
#/* Nama Program        : Marketing Fee                            */
#/* Dibuat pada tgl     : 10-September-2012                        */
#/*                Oleh : Purwanto                                 */
#/* Update terakhir     :   				                               */
#/*                oleh :                                          */
#/* Last Tested    - on :                                          */
#/*                - by :                                          */
#/* Status              :                                          */
#/******************************************************************/
#nama=`who am i |awk '{print $1}'`
while :
do
   clear
   #if [ $nama != admpmsdbs ];
   #     then
   #trap '' 2 20
   #fi
   tput cup 25 1
   echo "                             Copyright (c) 2012 ISCDM"
   echo "                             Information System CENTER"
   tput cup 1 1
   echo "                                                         "
   echo "           +--------------------------------------------------------+"
   echo "           |#########   UPLOAD DATA C2BI/Marketing Fee  ############|"
   echo "           +--------------------------------------------------------+"
   echo "           +--------------------------------------------------------+"
   echo "           |           1. Download Data Revenue                     |"
   echo "           |           2. update RIncian Tagihan                    |"
   echo "           |           3. Update detail Tag                         |"
   echo "           |           4. Marketing Fee                             |"
   echo "           |           5. Detail percakapan(AMA)                    |"
   #if [ $nama = admpmsdbs ];
   #     then
   echo "           |           6. hapus lock                                |"
   echo "           +--------------------------------------------------------+"
   echo "           |           0. Keluar                                    |"
   #fi
   echo "           +--------------------------------------------------------+"
   echo -n "                          Pilihan : "
   read Pilih
   case $Pilih in
     1) sh ./menu_1.sh;;
     2) sh ./menu_2.sh;;
     3) sh ./menu_3.sh;;
     4) sh ./menu_4.sh;;
     5) sh ./menu_5.sh;;
     
     0) exit;;



   esac

   echo " "
   echo -n "Tekan Enter untuk kembali ke menu sebelumnya ... "
   read Tekan
   done
