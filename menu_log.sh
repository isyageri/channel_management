#!/bin/bash
#/******************************************************************/
#/* Nama Program        : Eksekusi Ticket                          */
#/* Dibuat pada tgl     : 10-Juli- 2013                            */
#/*                Oleh : Purwanto                                 */
#/* Update terakhir     : 10-Juli- 2013                            */
#/*                oleh : Purwanto                                 */
#/* Last Tested    - on :                                          */
#/*                - by :                                          */
#/* Status              :                                          */
#/******************************************************************/
while :
do
   clear
   trap '' 2 20
   tput cup 25 1
   echo "                             Copyright (c) 2014  ISCDM"
   echo "                             Information System CENTER"
   tput cup 1 1
   echo "                                                         "
   echo "           +------------------------------------------------------+"
   echo "           | ################### LIHAT LOG #######################|"               
   echo "           +------------------------------------------------------+"
   echo "           |                                                      |"
   echo "           |               1. Lihat Log Menu 1                    |"
   echo "           |               2. Lihat Log Menu 2                    |"
   echo "           |               3. Lihat Log Menu 3                    |"                 
   echo "           |               4. Lihat Log Menu 4                    |"
   echo "           |               5. Lihat Log Menu 5                    |"                 
   echo "           |               6. Lihat Log Menu 6                    |"
   echo "           |                                                      |"
   echo "           +------------------------------------------------------|"
   echo "           |               q. Back                                |"
   echo "           +------------------------------------------------------+"
   echo -n "                   Pilihan : "
   read Pilih
   case $Pilih in
     1) sh menu_log_1.sh;;
     2) sh menu_log_2.sh;;
     3) sh menu_log_3.sh;;
     4) sh menu_log_4.sh;;
     5) sh menu_log_5.sh;;
     6) sh menu_log_6.sh;;
     q) exit;;
      
    
  
   esac

   echo " "
   echo -n "Tekan Enter untuk kembali ke menu sebelumnya ... "
   read Tekan
   done


