#!/bin/ksh
ORACLE_HOME=/u01/app/oracle/product/10.2.0/db_1
ORACLE_SID=dbs1;
PATH=$PATH:$ORACLE_HOME/bin:/home/marketfee
export ORACLE_HOME ORACLE_SID PATH
TGL=`date +"%Y%m"`
sqlplus marketfee/telkom135<<!
prompt"-----------------------------create table----------------------------------"
DECLARE
  CURSOR t1 IS
select  bil_period,
       due_date,
       clr_date,
       clr_doc,
       substr(zzgl_acc,3,8) gl_account,
       object_id account_num,
    sum(bill_amount) bill_ammount,
    decode(pay_status,'9','sudah_bayar','belum_bayar') pay_status  from   
    ods_trems.TREMS_REVENUE_NONPOTS@dwhnas_jkt
    where billing_type='IN'
    group by  bil_period,due_date,clr_date,clr_doc,zzgl_acc,object_id,pay_status; 
    w_c NUMBER(9) := 0;
BEGIN
FOR i IN t1
        LOOP
           w_c := w_c+1;
	   update TREMS_REVENUE_NONPOTS SET DUE_DATE=i.DUE_DATE,
	                                    CLR_DATE=i.CLR_DATE,
					    CLR_DOC=i.CLR_DOC,
                                            BILL_AMOUNT=i.BILL_AMOUNT,
					    PAY_STATUS=i.PAY_STATUS
					    WHERE BIL_PERIOD=i.BILL_PERIOD,
					          AND ACCOUNT_NUM=i.ACCOUNT_NUM
					          AND GL_ACCOUNT=i.GL_ACCOUNT;
	   IF MOD(W_C,10000)=0 THEN
		      COMMIT;
           END IF;
        END LOOP;
     DBMS_OUTPUT.PUT_LINE('Ter-Update = '||w_c||' sst'||' '||'.');
        COMMIT;
END;
/
