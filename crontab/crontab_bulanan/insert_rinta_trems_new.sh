#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=jepun;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
TGL=`date +"%Y%m"`
TGL1=`date +%Y%m -d '-1 month'`
THN1=`echo $TGL1|cut -c1-4`
BLN1=`echo $TGL1|cut -c5-6`
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`
BLN2=`echo $TGL|cut -c3-6`
#HCOM=H_COM_A`echo $TGL|cut -c1-4`_P`echo $TGL|cut -c5-6`_TA@ODSNAS
sqlplus  c2bi/telkom123@jepunrac<<!
prompt"---------------gat data H_EXPEDITION-----------------"

set serveroutput on

  DROP TABLE TREMS_REKAP_REGALL_$TGL
/
create table TREMS_REKAP_REGALL_$TGL as
select substr(period,1,4) an_fact,
       substr(period,5,2) per_fact,
               NCLI,
 to_number(NDOS) NDOS,
            telnum ND,
         CCAT SEGMENT,
         DOC_NUMBER,
             ABONEMEN,
               LOKAL,
                SLJJ,
              DISKON,
          INTERLOKAL,
             TGLOBAL,
      SLI_NON_TELKOM,
         SLI_TELKOM,
             JAPATI,
PONSEL_LOKAL+PONSEL_SLJJ STB,
                TENI,
    TSAVE TELKOMSAVE,
          NON_JASTEL,
               FITUR,
          SMS KONTEN,
       TAGIHAN TOTAL,
                 PPN,
   TOTAL GRAND_TOTAL,
             MATERAI,
              GSBER,
              CPROD,
              DIVRE  from  odspots.TREMS_REKAP_REGALL_$TGL@DWHNAS_BDG 
/
create index trems_01_$TGL on TREMS_REKAP_REGALL_$TGL (DIVRE,CPROD)
/
create index trems_02_$TGL on TREMS_REKAP_REGALL_$TGL (ncli,ndos)
/ 
create index trems_03_$TGL on TREMS_REKAP_REGALL_$TGL (nd)
/
           
    

prompt"--------------------Insert into cust_rinta------------------"
ALTER TABLE cust_rinta DROP PARTITION period_$TGL
/
alter table cust_rinta add partition period_$TGL VALUES LESS THAN ($THN1, $BLN1)
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
         to_number(nvl(TOTAL,0)) TOTAL from TREMS_REKAP_REGALL_$TGL h,(select distinct nd from ten_nd) b
where h.nd=b.nd;
w_c NUMBER(9) := 0;
BEGIN
FOR i IN t1
        LOOP
           w_c := w_c+1;
           insert into cust_rinta partition(period_$TGL) ( 
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
   cursor c1 is
 select ncli,ndos,nom from cust_rinta partition(period_201409) ;
 w_c number(9) := 0;
 begin
   for i in c1
 loop
 w_c := w_c+1;
 UPDATE  cust_rinta   
            SET NOM=i.NOM
           WHERE NCLI=i.NCLI AND NDOS=i.NDOS AND AN_FACT=$THN AND PER_FACT=$BLN
           and NOM is null;                             
       IF MOD(W_C,1000)=0 THEN
      COMMIT;
       END IF;
    end loop;
   dbms_output.put_line('Ter-Update = '||w_c||' ssl'||' '||'');
   commit;
 end;
/
 

declare
cursor c1 is select  a.nper,
	                   a.telp,
	                   a.payment_date,
	                   sum(a.jumlah_bayar) jumlah_bayar
 from trems_payment_$TGL a,cust_rinta b
    where  a.telp=b.nd ---and nper='$TGL'  
    and an_fact=$THN and per_fact=$BLN 
    group by a.nper,a.telp,a.payment_date;
w_c number(15) := 0;
begin
    for i in c1
loop
w_c := w_c+1;
update cust_rinta  set tgl_byr=i.payment_date,
                           status_pembayaran='sudah bayar',
                           jumlah_bayar=i.jumlah_bayar
                           where nd=i.telp 
                           and an_fact=$THN 
                           and per_fact=$BLN;
   IF MOD(W_C,1000)=0 THEN
      COMMIT;
         END IF;
     end loop;
   dbms_output.put_line('upate_byr = '||w_c||' ssl'||' '||'');
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

!
exit
