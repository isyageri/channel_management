#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=jepun;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
##TGL=`date +"%Y%m"`
TGL=$1
sqlplus c2bi/telkom123@jepunrac<<!
set serveroutput on
DROP TABLE RINTA_ADD_$TGL
/
CREATE TABLE RINTA_ADD_$TGL
(
  AN_FACT            NUMBER(4),
  PER_FACT           NUMBER,
  NO_TAGIHAN         VARCHAR2(15 BYTE),
  ND                 VARCHAR2(15 BYTE),
  NCLI               NUMBER(8),
  NDOS               NUMBER(4),
  ABONEMEN           NUMBER(26,6)               DEFAULT 0,
  QUOTA              NUMBER(26,6)               DEFAULT 0,
  MNT_TCK_D          NUMBER(26,6)               DEFAULT 0,
  MNT_TCK_C          NUMBER(26,6)               DEFAULT 0,
  PPN                NUMBER(26,6)               DEFAULT 0,
  METERAI            NUMBER                     DEFAULT 0,
  TOTAL              NUMBER(26,6)               DEFAULT 0,
  LOKAL              NUMBER                     DEFAULT 0,
  SLJJ               NUMBER                     DEFAULT 0,
  STB                NUMBER                     DEFAULT 0,
  JAPATI             NUMBER                     DEFAULT 0,
  SLI007             NUMBER                     DEFAULT 0,
  SLI001             NUMBER                     DEFAULT 0,
  SLI008             NUMBER                     DEFAULT 0,
  SLI009             NUMBER                     DEFAULT 0,
  SLI_017            NUMBER                     DEFAULT 0,
  INTERLOKAL         NUMBER                     DEFAULT 0,
  ISDN_DATA          NUMBER                     DEFAULT 0,
  ISDN_VOICE         NUMBER                     DEFAULT 0,
  TELKOMNET_INSTAN   NUMBER                     DEFAULT 0,
  TELKOMSAVE         NUMBER                     DEFAULT 0,
  NON_JASTEL         NUMBER                     DEFAULT 0,
  USAGE_SPEEDY       NUMBER                     DEFAULT 0,
  KONTEN             NUMBER                     DEFAULT 0,
  PORTWHOLESALES     NUMBER                     DEFAULT 0,
  STATUS_PEMBAYARAN  VARCHAR2(12 BYTE),
  JUMLAH_BAYAR       NUMBER                     DEFAULT 0,
  TGL_BYR            VARCHAR2(14 BYTE),
  CPROD              NUMBER,
  NOM                VARCHAR2(100 BYTE),
  GRAND_TOTAL        NUMBER
)
/
CREATE INDEX C2BI.I_RINTA_ADD_$TGL ON C2BI.RINTA_ADD_$TGL (ND)
LOGGING
/
CREATE INDEX C2BI.I_RINTA_ADD_IDX_$TGL ON C2BI.RINTA_ADD_$TGL (NCLI, NDOS)
LOGGING
/
DECLARE
  CURSOR t1 IS
 select  an_fact,
         per_fact,
         h.ncli,
         h.ndos,
         h.nd,
         cprod,
         to_number(nvl(abonemen,0)) abonemen,
         to_number(nvl(diskon,0)) mnt_tck_c,
         to_number(nvl(ppn,0)) ppn,
         to_number(nvl(MATERAI,0)) meterai,
         to_number(nvl(grand_total,0)) GRAND_TOTAL,
         to_number(nvl(lokal,0)) lokal,
         to_number(nvl(sljj,0)) sljj,
         to_number(nvl(stb,0)) stb,
         to_number(nvl(japati,0)) japati,
         to_number(nvl(sli_telkom,0)) sli007,
         to_number(nvl(sli_non_telkom,0)) sli001,
         to_number(nvl(TGLOBAL,0)) SLI_017,
         to_number(nvl(interlokal,0)) interlokal,
         to_number(nvl(TENI,0)) TELKOMNET_INSTAN,
         to_number(nvl(TELKOMSAVE,0)) telkomsave,
         to_number(nvl(NON_JASTEL,0)) non_jastel,
         to_number(nvl(fitur,0))+to_number(nvl(KONTEN,0)) konten,
         to_number(nvl(TOTAL,0)) TOTAL from TREMS_REKAP_REGALL_$TGL  h,(select distinct trim(nd) from ten_nd
minus
select trim(nd) from cust_rinta partition (period_$TGL)) b
where h.nd=b.nd;
w_c NUMBER(9) := 0;
BEGIN
FOR i IN t1
        LOOP
           w_c := w_c+1;
  					insert into RINTA_ADD_$TGL ( 
                                 AN_FACT, 
                                 PER_FACT,
	                               NCLI,
                                 NDOS,
                                 ND,
                                 ABONEMEN,
                                 mnt_tck_c,
	                               PPN,
				                         METERAI,
				                         TOTAL,
				                         LOKAL,
				                         SLJJ,
				                         STB,
				                         JAPATI,
				                         SLI007,
				                         SLI001,
				                         SLI_017,
				                         INTERLOKAL,
				                         TELKOMNET_INSTAN,
				                         TELKOMSAVE,
				                         NON_JASTEL,
				                         KONTEN,
				                         GRAND_TOTAL,
				                         cprod
				                         )
          		         values 
          		             (    i.AN_FACT,
          		                   i.PER_FACT,
	                               i.NCLI,
                                 i.NDOS,
                                 i.ND,
                                 i.ABONEMEN,
                                 i.mnt_tck_c,
	                               i.PPN,
				                         i.METERAI,
				                         i.TOTAL,
				                         i.LOKAL,
				                         i.SLJJ,
				                         i.STB,
				                         i.JAPATI,
				                         i.SLI007,
				                         i.SLI001,
				                         i.SLI_017,
				                         i.INTERLOKAL,
				                         i.TELKOMNET_INSTAN,
				                         i.TELKOMSAVE,
				                         i.NON_JASTEL,
				                         i.KONTEN,
				                         i.GRAND_TOTAL,
				                         i.cprod);								      
                    IF MOD(W_C,1000)=0 THEN
		      COMMIT;
           END IF;
        END LOOP;
     DBMS_OUTPUT.PUT_LINE('Ter-Update = '||w_c||' sst'||' '||'.');
        COMMIT;
END;
/

declare
cursor c1 is select  a.nper,a.telp,a.payment_date
   from ods_trems.trems_payment@DWHNAS_JKT a,RINTA_ADD_$TGL b
    where  a.telp=b.nd 
         and nper='$TGL'
         and substr(cpudt,1,6)='$TGL'
         AND NVL (a.pay_status, 'x') <> '3'  
    group by nper,telp,payment_date;
w_c number(15) := 0;
begin
    for i in c1
loop
w_c := w_c+1;
update RINTA_ADD_$TGL  set tgl_byr=i.payment_date,
                           status_pembayaran='sudah bayar'
                           where nd=i.telp;
   IF MOD(W_C,10000)=0 THEN
      COMMIT;
         END IF;
     end loop;
   dbms_output.put_line('upate_byr = '||w_c||' ssl'||' '||'');
  commit;
end;
/
declare
cursor c1 is select a.ncli,a.nom from client@DBL_FBIP.REGRESS.RDBMS.DEV.US.ORACLE.COM a,
	          RINTA_ADD_$TGL b where a.ncli=b.ncli ;
w_c number(15) := 0;
begin
    for i in c1
loop
w_c := w_c+1;
update RINTA_ADD_$TGL  set nom=i.nom
                           where ncli=i.ncli and nom is null;
   IF MOD(W_C,10000)=0 THEN
      COMMIT;
         END IF;
     end loop;
   dbms_output.put_line('upate_byr = '||w_c||' ssl'||' '||'');
  commit;
end;
/
DECLARE
        cnt NUMBER(5);
        I_TEN_ID NUMBER(10);
        v_err VARCHAR2(20);
        V_STATUS_BAYAR VARCHAR2(1);
CURSOR cursor_1 IS
select  an_fact,
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
       from rinta_ADD_$TGL a,
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
        delete from ten_usage where period=$TGL and nd in (select nd from rinta_ADD_$TGL);
	commit;
        delete from ten_payment where period=$TGL and nd in (select nd from rinta_ADD_$TGL);
	commit;
	delete from cust_rinta partition(period_$TGL) where  nd in (select nd from rinta_ADD_$TGL);
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
        INSERT INTO CUST_RINTA  select * from rinta_ADD_$TGL;
	      commit;
        DELETE FROM TEN_USAGE WHERE PERIOD=$TGL AND CF_NOM IS NULL;
        
        COMMIT;

END;
/
!
exit
