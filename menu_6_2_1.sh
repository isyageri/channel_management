#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=jepun;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
TGL=$1
#TGL=`date +"%Y%m"`
#TGL1=`date +%Y%m -d '1 month'`
#THN1=`echo $TGL1|cut -c1-4`
#BLN1=`echo $TGL1|cut -c5-6`
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`
BLN2=`echo $TGL|cut -c3-6`
#HCOM=H_COM_A`echo $TGL|cut -c1-4`_P`echo $TGL|cut -c5-6`_TA@ODSNAS
sqlplus  c2bi/telkom123@jepunrac<<!

prompt"-------------------create conso_agregat------------------"

drop TABLE F_CONSO_AGREGAT CASCADE CONSTRAINTS
/
CREATE TABLE F_CONSO_AGREGAT
(
  AN_FACT       NUMBER(4)                       NOT NULL,
  PER_FACT      NUMBER(2)                       NOT NULL,
  NCLI          NUMBER(8)                       NOT NULL,
  NDOS          NUMBER(4)                       NOT NULL,
  CODE_AGREGAT  NUMBER(4)                       NOT NULL,
  MONTANT       NUMBER(26,6)
)
/
CREATE INDEX H_I_CONSO ON 
f_conso_agregat (NCLI, NDOS) 
/ 
  commit
/
declare
 CURSOR rec_cur IS 
 select   ncli,
          ndos from  
          cust_rinta partition(period_$TGL);  
             w_c number := 0;
         TYPE num_tab_t IS TABLE OF NUMBER(38);
         TYPE vc2_tab_t IS TABLE OF VARCHAR2(4000);
         TYPE date_tab_t IS TABLE OF DATE;              
            v_an_fact    NUM_TAB_T;
            v_per_fact   NUM_TAB_T;
            v_ncli       NUM_TAB_T;
            v_ndos       NUM_TAB_T;
            v_code_agregat      NUM_TAB_T;
            v_montant  NUM_TAB_T;
      BEGIN
 FOR e IN rec_cur
     LOOP
     	 w_c := w_c + 1;
     	  select an_fact,
     	         per_fact,
     	         ncli,
     	         ndos,
     	         code_agregat,
     	         montant 
     	         bulk collect into v_an_fact,v_per_fact,v_ncli,v_ndos,v_code_agregat,v_montant 
         from H_F_CONSO_AGREGAT@DBL_FBIP.REGRESS.RDBMS.DEV.US.ORACLE.COM
             where   an_fact= $THN 
             and     per_fact= $BLN
             and     ncli=e.ncli
             and     ndos=e.ndos 
             and groupe_fact = 'A';
       FORALL i IN v_ncli.FIRST .. v_ncli.LAST
            insert into F_CONSO_AGREGAT values (v_an_fact(i),v_per_fact(i),v_ncli(i),v_ndos(i),v_code_agregat(i),v_montant(i));
                 IF MOD(w_c,100)=0
                    THEN COMMIT;
                    END IF;   
          EXIT WHEN rec_cur%NOTFOUND;                       
    END LOOP;
     dbms_output.put_line('Ter-insert = '||w_c||' nd'||' '||''); 
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
     f_conso_agregat hfc
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
                   
       IF MOD(W_C,1000)=0 THEN
      COMMIT;
       END IF;
    end loop;
   dbms_output.put_line('Ter-Update = '||w_c||' ssl'||' '||'');
   commit;
 end;
/

ALTER TABLE ten_usage truncate PARTITION period_$TGL;
/
commit
/
ALTER TABLE ten_payment truncate PARTITION period_$TGL;
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
/*
prompt"----------------------insert FLEXI----------------------"
DECLARE
                CURSOR t1  IS
		select * from odsflexi.REV_DETAIL_109_FLEXI_$BLN2@DWHNAS_JKT 
		where notel in (select distinct nd notel from ten_nd where cprod='9') 
		and REVENUE_TYPE_ID=to_char('080101');
		 w_c NUMBER(9) := 0;
BEGIN
delete from CUST_RINTA_FLX where periode='$TGL';
FOR i IN t1
        LOOP
           w_c := w_c+1;

	   insert into CUST_RINTA_FLX
                        (
			PERIODE,
			  DATEL_ID,
			  LINECATS_ITEM_ID,
			  PRODUCT_LINE_ID,
			  REVENUE_TYPE_ID,
			  NCLI,
			  NDOS,
			  NOTEL,
			  REVENUE,
			  ACCOUNT_NUM,
			  CPROD
                         )
	                VALUES
			( i.PERIODE,
			  i.DATEL_ID,
			  i.LINECATS_ITEM_ID,
			  i.PRODUCT_LINE_ID,
			  i.REVENUE_TYPE_ID,
			  i.NCLI,
			  i.NDOS,
			  i.NOTEL,
			  i.REVENUE,
			  i.ACCOUNT_NUM,
			   9
			   );
	   IF MOD(W_C,1000)=0 THEN
		      COMMIT;
           END IF;
        END LOOP;
     DBMS_OUTPUT.PUT_LINE('Ter-Update = '||w_c||' sst'||' '||'.');
        COMMIT;
END;
/
prompt"--------------------------insert into marketing fee------------------"
DECLARE
        cnt NUMBER(5);
        I_TEN_ID NUMBER(10);
        v_err VARCHAR2(20);
        V_STATUS_BAYAR VARCHAR2(1);
CURSOR cursor_1 IS
select PERIODE PERIOD,
        NOTEL ND,
       '0' NCLI,
       '0' NDOS,
        REVENUE CF_NOM,
	STATUS_PEMBAYARAN
       from CUST_RINTA_FLX a,
            ten_nd b,
            pgl_ten c,
            cust_pgl d 
       where a.notel=b.nd
       and b.TEN_ID=c.TEN_ID
       and c.PGL_ID=d.PGL_ID
       and d.ENABLE_FEE=1
       and b.CPROD=9
       and a.REVENUE_TYPE_ID=to_char('080101');
              fs cursor_1%ROWTYPE;

BEGIN
        
	      delete from ten_usage where period=to_char($TGL) and nd in (select notel nd from CUST_RINTA_FLX);
	commit;
        delete from ten_payment where period=to_char($TGL) and nd in (select notel nd from CUST_RINTA_FLX);
	commit;
        COMMIT;
        OPEN cursor_1;
        cnt:=0;
        LOOP FETCH cursor_1 INTO fs;
                EXIT WHEN cursor_1%NOTFOUND;
                cnt:=cnt+1;
                BEGIN
                                            
                        INSERT INTO TEN_USAGE(PERIOD, NCLI, NDOS, ND, CF_ID, CF_NOM) VALUES ($TGL, fs.NCLI, fs.NDOS, fs.ND, 1597, fs.CF_NOM);
                        
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
*/
!
exit
