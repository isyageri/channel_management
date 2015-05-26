#!/bin/ksh
#TGL=`date +"%Y%m"`
TGL=201311
TGL1=`date +%Y%m -d '1 month'`
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`
BLN2=`echo $TGL|cut -c3-6`

sqlplus  c2bi/telkom123@jepunrac<<!

DECLARE
        cnt NUMBER(9);
        I_TEN_ID NUMBER(10);
        v_err VARCHAR2(20);
        V_STATUS_BAYAR VARCHAR2(15);
CURSOR cursor_1 IS
select an_fact,
       per_fact, 
       a.ncli, 
       a.ndos, 
       a.nd, 
       abonemen, 
       quota, 
       mnt_tck_d, 
       mnt_tck_c,
       ppn, 
       meterai, 
       total, 
       lokal, 
       sljj, 
       stb, 
       japati, 
       sli007, 
       sli001, 
       sli008,
       sli009, 
       sli_017, 
       interlokal,
       isdn_data, 
       isdn_voice, 
       telkomnet_instan, 
       telkomsave, 
       non_jastel, 
       usage_speedy, 
       konten, 
       portwholesales,
       STATUS_PEMBAYARAN, 
       a.cprod
       from cust_rinta partition(period_$TGL)a,
           (select distinct nd from 
             ten_nd a,
             pgl_ten b,
             cust_pgl c
       where  a.TEN_ID=b.TEN_ID
          and b.PGL_ID=c.PGL_ID
          and c.ENABLE_FEE=1) b
     where  a.nd=b.nd;
              fs cursor_1%ROWTYPE;

BEGIN
        EXECUTE IMMEDIATE('ALTER TABLE ten_usage TRUNCATE PARTITION PERIOD_$TGL');
        EXECUTE IMMEDIATE('ALTER TABLE ten_payment TRUNCATE PARTITION PERIOD_$TGL');
        COMMIT;
        OPEN cursor_1;
        cnt:=0;
        LOOP FETCH cursor_1 INTO fs;
                EXIT WHEN cursor_1%NOTFOUND;
                cnt:=cnt+1;
                BEGIN
                        IF fs.CPROD=11
                        THEN
                            INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 79, fs.ABONEMEN);
                        ELSE
                            INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 20, fs.ABONEMEN);
                        END IF;
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 21, fs.QUOTA);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 22, fs.MNT_TCK_D);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 23, fs.MNT_TCK_C);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 26, fs.PPN);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 25, fs.METERAI);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 27, fs.TOTAL);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 1, fs.LOKAL);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 2, fs.SLJJ);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 11, fs.STB);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 13, fs.JAPATI);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 6, fs.SLI007);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 5, fs.SLI001);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 7, fs.SLI008);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 8, fs.SLI009);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 4, fs.SLI_017);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 3, fs.INTERLOKAL);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 9, fs.ISDN_DATA);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 10, fs.ISDN_VOICE);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 14, fs.TELKOMNET_INSTAN);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 15, fs.TELKOMSAVE);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 16, fs.NON_JASTEL);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 17, fs.USAGE_SPEEDY);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 19, fs.KONTEN);
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 24, fs.PORTWHOLESALES);
                        
                        --- sudah bayar
                        IF fs.STATUS_PEMBAYARAN='sudah bayar' 
                        THEN
                        V_STATUS_BAYAR := '1';
                        ELSE
                        V_STATUS_BAYAR := '0';
                        END IF;
                        INSERT INTO TEN_PAYMENT(PERIOD, NCLI, NDOS, ND, IS_PAID) VALUES($TGL, fs.NCLI, fs.NDOS, fs.ND, V_STATUS_BAYAR);
                END;
                IF MOD(cnt,5000)=0
                THEN COMMIT;
                END IF;
        END LOOP;
        COMMIT;
        
        DELETE FROM TEN_USAGE WHERE PERIOD=$TGL AND CF_NOM IS NULL;
        
        COMMIT;

END;
/
!
exit