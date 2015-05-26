#!/bin/ksh
#TGL=`date +"%Y%m" '1 month'`
TGL=$1
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`
BLN2=`echo $TGL|cut -c3-6`
#HCOM=H_COM_A`echo $TGL|cut -c1-4`_P`echo $TGL|cut -c5-6`_TA@ODSNAS
sqlplus  c2bi/telkom123@pangrango<<!
set serveroutput on

prompt"--------------------Insert into cust_rinta------------------"
ALTER TABLE cust_rinta DROP PARTITION period_$TGL;
/
alter table cust_rinta add partition period_$TGL VALUES LESS THAN ($THN1, $BLN1)
/
DECLARE
  CURSOR t1 IS
 select h.an_fact,
        h.per_fact,
        h.nfact NO_TAGIHAN,
				h.ncli,
				h.ndos,
				h.nd,
        h.mnt_abo ABONEMEN,
				h.mnt_regabo QUOTA,
				h.mnt_tck_d,
				h.mnt_tck_c,
				h.mnt_taxe_fact PPN,
        h.meterai,
				h.MNT_HT_FACT total,
				h.cprod,
				h.nom,
				h.mnt_taxe_fact+h.MNT_HT_FACT+h.meterai GRAND_TOTAL
FROM h_expedition partition(period_$TGL) h,(select distinct nd from ten_nd) b
where h.nd=b.nd;
w_c NUMBER(9) := 0;
BEGIN
FOR i IN t1
        LOOP
           w_c := w_c+1;
           insert into cust_rinta partition(period_$TGL) ( AN_FACT, 
                                 PER_FACT,
	                               NO_TAGIHAN,
                                 NCLI,
                                 NDOS,
                                 ND,
                                 ABONEMEN,
                                 QUOTA,
                                 mnt_tck_d,
	                               MNT_TCK_C,
                                 PPN,
				                         METERAI,
				                         TOTAL,
				                         cprod,
				                         nom,
				                         GRAND_TOTAL)
          		         values 
          		             (     i.AN_FACT, 
                                 i.PER_FACT,
														      i.NO_TAGIHAN,
														      i.NCLI,
														      i.NDOS,
														      i.ND,
														      i.ABONEMEN,
														      i.QUOTA,
														      i.mnt_tck_d,
														      i.MNT_TCK_C,
														      i.PPN,
														      i.METERAI,
														      i.TOTAL,
														      i.cprod,
														      i.nom,
														      i.GRAND_TOTAL);
                    IF MOD(W_C,1000)=0 THEN
		      COMMIT;
           END IF;
        END LOOP;
     DBMS_OUTPUT.PUT_LINE('Ter-Update = '||w_c||' sst'||' '||'.');
        COMMIT;
END;
/
prompt"-------------------Update Detail Tagihan--------------------"
 declare
   cursor c1 is
   SELECT hfc.ncli,
          hfc.ndos,
          SUM(NVL(DECODE(hfc.code_agregat,1,hfc.montant,0),0)) LOKAL,
          SUM(NVL(DECODE(hfc.code_agregat,2,hfc.montant,0),0)) SLJJ,
          SUM(NVL(DECODE(hfc.code_agregat,3,hfc.montant,0),0)) INTERLOKAL,
          SUM(NVL(DECODE(hfc.code_agregat,4,hfc.montant,0),0)) SLI_017,
          SUM(NVL(DECODE(hfc.code_agregat,5,hfc.montant,0),0)) SLI001,
          SUM(NVL(DECODE(hfc.code_agregat,6,hfc.montant,0),0)) SLI007,
          SUM(NVL(DECODE(hfc.code_agregat,7,hfc.montant,0),0)) SLI008,
          SUM(NVL(DECODE(hfc.code_agregat,8,hfc.montant,0),0)) SLI009,
          SUM(NVL(DECODE(hfc.code_agregat,9,hfc.montant,0),0)) ISDN_DATA,
          SUM(NVL(DECODE(hfc.code_agregat,10,hfc.montant,0),0)) ISDN_VOICE,
          SUM(NVL(DECODE(hfc.code_agregat,11,hfc.montant,0),0))+
          SUM(NVL(DECODE(hfc.code_agregat,12,hfc.montant,0),0)) STB,
          SUM(NVL(DECODE(hfc.code_agregat,13,hfc.montant,0),0)) JAPATI,
          SUM(NVL(DECODE(hfc.code_agregat,14,hfc.montant,0),0)) TELKOMNET_INSTAN,
          SUM(NVL(DECODE(hfc.code_agregat,15,hfc.montant,0),0)) TELKOMSAVE,
          SUM(NVL(DECODE(hfc.code_agregat,16,hfc.montant,0),0)) NON_JASTEL,
	  SUM(NVL(DECODE(hfc.code_agregat,17,hfc.montant,0),0))+
          SUM(NVL(DECODE(hfc.code_agregat,18,hfc.montant,0),0)) USAGE_SPEEDY,
          SUM(NVL(DECODE(hfc.code_agregat,19,hfc.montant,0),0)) KONTEN,
	  SUM(NVL(DECODE(hfc.code_agregat,20,hfc.montant,0),0)) PORTWHOLESALES
            FROM  
     h_f_conso_agregat hfc
            group by hfc.ncli,hfc.ndos;
 w_c number(9) := 0;
 begin
   for i in c1
 loop
 w_c := w_c+1;
 UPDATE  cust_rinta   SET LOKAL=i.LOKAL,
			      SLJJ=i.SLJJ,
			      STB=i.STB,
			      JAPATI=i.JAPATI,
			      SLI007=i.SLI007,
			      SLI001=i.SLI001,
			      SLI008=i.SLI008,
			      SLI009=i.SLI009,
			      SLI_017=SLI_017,
			      INTERLOKAL=i.INTERLOKAL,
			      ISDN_DATA=i.ISDN_DATA,
			      ISDN_VOICE=i.ISDN_VOICE,
			      TELKOMNET_INSTAN=i.TELKOMNET_INSTAN,
			      TELKOMSAVE=i.TELKOMSAVE,
			      NON_JASTEL=i.NON_JASTEL,
			      USAGE_SPEEDY=USAGE_SPEEDY,
			      KONTEN=i.KONTEN,
			      PORTWHOLESALES=i.PORTWHOLESALES
			      WHERE NCLI=I.NCLI AND NDOS=I.NDOS AND AN_FACT= $THN AND PER_FACT= $BLN;             
                   
       IF MOD(W_C,10000)=0 THEN
      COMMIT;
       END IF;
    end loop;
   dbms_output.put_line('Ter-Update = '||w_c||' ssl'||' '||'');
   commit;
 end;
/

ALTER TABLE ten_usage DROP PARTITION period_$TGL;
/
commit
/
alter table ten_usage add PARTITION PERIOD_$TGL VALUES ('$TGL')
/
commit
/
ALTER TABLE ten_payment DROP PARTITION period_$TGL;
/
commit
/
alter table ten_payment add  PARTITION PERIOD_$TGL VALUES ('$TGL')
/
commit
/
prompt"---------------------Update marketing fee $TGL------------------"
DECLARE
        cnt NUMBER(5);
        I_TEN_ID NUMBER(10);
        v_err VARCHAR2(20);
        V_STATUS_BAYAR VARCHAR2(1);
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
            ten_nd b,
            pgl_ten c,
            cust_pgl d 
       where a.nd=b.nd
       and b.TEN_ID=c.TEN_ID
       and c.PGL_ID=d.PGL_ID
       and d.ENABLE_FEE=1;
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
                IF MOD(cnt,50000)=0
                THEN COMMIT;
                END IF;
        END LOOP;
        COMMIT;
        
        DELETE FROM TEN_USAGE WHERE PERIOD=$TGL AND CF_NOM IS NULL;
        
        COMMIT;

END;
/
select to_char(sysdate,'DD-MONTH-YYYY HH24:mi:ss') SELESAI from dual
/
!
exit
