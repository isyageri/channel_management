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
                              WHERE NCLI=I.NCLI AND NDOS=I.NDOS AND AN_FACT=2012 AND PER_FACT=04;
       IF MOD(W_C,10000)=0 THEN
      COMMIT;
       END IF;
    end loop;
   dbms_output.put_line('Ter-Update = '||w_c||' ssl'||' '||'');
   commit;
 end;
/

