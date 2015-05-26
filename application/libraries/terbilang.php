<?php
	
Class Terbilang {
	private $dasar;
	private $angka;
	private $satuan;
	
    public function __construct() {
        $this->dasar = array(1=>'satu','dua','tiga','empat','lima','enam','tujuh','delapan','sembilan');
        $this->angka = array(1000000000,1000000,1000,100,10,1);
        $this->satuan = array('milyar','juta','ribu','ratus','puluh','');
    }

    public function eja($n) {
		$str = ""; $count = 0;
        $i=0;
        while($n!=0){
            $count = (int)($n/$this->angka[$i]);
            if($count>=10) 
				$str .= $this->eja($count). " ".$this->satuan[$i]." ";
            else if($count > 0 && $count < 10)
				$str .= $this->dasar[$count] . " ".$this->satuan[$i]." ";
            $n -= $this->angka[$i] * $count;
            $i++;
        }
        $str = preg_replace("/satu puluh (\w+)/i","\\1 belas",$str);
        $str = preg_replace("/satu (ribu|ratus|puluh|belas)/i","se\\1",$str);
        return $str;
    }
}
?>