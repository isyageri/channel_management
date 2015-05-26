PERIOD=201202
THN=`echo $PERIOD|cut -c1-4`
BLN=`echo $PERIOD|cut -c5-6`
THN1=`expr $THN - 1`
#BLN=08



if [[ $BLN = 01  ]]
               then
                PERIOD1=`expr $THN - 1`12
                 else
                PERIOD1=`expr $PERIOD - 1`
        fi

echo "$PERIOD1"