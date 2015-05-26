#!/bin/ksh
ORACLE_HOME=/home/oracle/oracle/product/10.2.0/db_1
ORACLE_SID=pangrango;
PATH=$PATH:$ORACLE_HOME/bin:/home/adm-marketfee
export ORACLE_HOME ORACLE_SID PATH
TGL=$1
THN=`echo $TGL|cut -c1-4`
BLN=`echo $TGL|cut -c5-6`
DBL=@ODSNAS
#DBL=@DBL_FBIP.REGRESS.RDBMS.DEV.US.ORACLE.COM

sqlplus  c2bi/telkom123@pangrango<<!
set serverout on

prompt"--------------------Insert into cust_rinta------------------"
ALTER TABLE cust_rinta DROP PARTITION period_$TGL
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
           EXIT WHEN c1%NOTFOUND;
        END LOOP;
     DBMS_OUTPUT.PUT_LINE('Ter-Update = '||w_c||' sst'||' '||'.');
        COMMIT;
END;
/

prompt"-------------------Update Detail Tagihan--------------------"
 declare
   cursor c1 is
   SELECT rownum row_num,
          hfc.ncli,
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
            group by rownum,hfc.ncli,hfc.ndos;
            
 TYPE t_num_array  IS TABLE OF NUMBER INDEX BY BINARY_INTEGER;
 TYPE t_char_array IS TABLE OF VARCHAR2(10) INDEX BY BINARY_INTEGER;
  
  v_rownum     t_num_array;
  v_ncli       t_num_array;
  v_ndos       t_num_array;
  v_lokal      t_num_array;
  v_sljj       t_num_array;
  v_interlokal t_num_array;
  v_sli_017    t_num_array;
  v_sli001     t_num_array;
  v_sli007     t_num_array;
  v_sli008     t_num_array;
  v_sli009     t_num_array;
  v_isdn_data  t_num_array;
  v_isdn_voice t_num_array;
  v_stb        t_num_array;
  v_japati     t_num_array;
  v_telkomnet_instan t_num_array;
  v_telkomsave t_num_array;
  v_non_jastel t_num_array;
  v_usage_speedy t_num_array;
  v_konten     t_num_array;
  v_portwholesales t_num_array;
  v_row_count      NUMBER := 0; 
  w_c number := 0;
 
 begin
   
   open c1;
 loop
                 w_c := w_c+1;
                         FETCH c1
                         BULK COLLECT INTO 
                        v_rownum,
                        v_ncli,
                        v_ndos,
											  v_lokal,
											  v_sljj,
											  v_interlokal,
											  v_sli_017,
											  v_sli001,
											  v_sli007,
											  v_sli008,
											  v_sli009,
											  v_isdn_data,
											  v_isdn_voice,
											  v_stb,
											  v_japati,
											  v_telkomnet_instan,
											  v_telkomsave,
											  v_non_jastel,
											  v_usage_speedy,
											  v_konten,
											  v_portwholesales
                         LIMIT 10000;
            EXIT WHEN v_row_count = c1%ROWCOUNT;
            v_row_count := c1%ROWCOUNT;
 FOR i IN 1..v_rownum.count loop
            v_rownum(i) := v_rownum(i);
            v_ncli(i)   := v_ncli(i);
            v_ndos(i)   := v_ndos(i);
						v_lokal(i)	 := v_lokal(i);
						v_sljj(i)	 :=			  v_sljj(i);
						v_interlokal(i)	:=			v_interlokal(i);
						v_sli_017(i)			:=		  v_sli_017(i);
						v_sli001(i)			:=		  v_sli001(i);
						v_sli007(i)			:=		  v_sli007(i);
						v_sli008(i)			:=		  v_sli008(i);
						v_sli009(i)			:=		  v_sli009(i);
						v_isdn_data(i)   :=      v_isdn_data(i);
						v_isdn_voice(i)	:=			v_isdn_voice(i);
						v_stb(i)					:=      v_stb(i);
						v_japati(i)			:=		  v_japati(i);
						v_telkomnet_instan(i)	:=	  v_telkomnet_instan(i);
						v_telkomsave(i)				:=	  v_telkomsave(i);
						v_non_jastel(i)				:=	  v_non_jastel(i);
						v_usage_speedy(i)				:=	  v_usage_speedy(i);
						v_konten(i)					  :=    v_konten(i);
						v_portwholesales(i)		:=	  v_portwholesales(i);
						end loop;
FORALL i IN 1..v_rownum.count						
 UPDATE  cust_rinta   
            SET LOKAL=v_lokal(i),
			      SLJJ=v_sljj(i),
			      STB=v_stb(i),
			      JAPATI=v_japati(i),
			      SLI007=v_sli007(i),
			      SLI001=v_sli001(i),
			      SLI008=v_sli008(8),
			      SLI009=v_sli009(i),
			      SLI_017=v_sli_017(i),
			      INTERLOKAL=v_interlokal(i),
			      ISDN_DATA=v_isdn_data(i),
			      ISDN_VOICE=v_isdn_voice(i),
			      TELKOMNET_INSTAN=v_telkomnet_instan(i),
			      TELKOMSAVE=v_telkomsave(i),
			      NON_JASTEL=v_non_jastel(i),
			      USAGE_SPEEDY=v_usage_speedy(i),
			      KONTEN=v_konten(i),
			      PORTWHOLESALES=v_portwholesales(i)
			      WHERE NCLI=v_ncli(i) AND NDOS=v_ndos(i) AND AN_FACT= $THN AND PER_FACT= $BLN;             
                   
          COMMIT;
       EXIT WHEN c1%NOTFOUND;
       
    end loop;
   dbms_output.put_line('Ter-Update = '||w_c||' ssl'||' '||'');
   CLOSE c1;
   commit;
 end;
/

select count(*) Jumlah_row from cust_rinta where AN_FACT=$THN AND PER_FACT=$BLN
/
exit
!