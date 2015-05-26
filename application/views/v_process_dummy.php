<div class='title'>Nota Perhitungan Keuangan (NPK) Marketing Fee</div>
<?php
echo "Pengelola : ".form_dropdown("pengelola", array(0=>"Karya Bangun", 1=>"Kenari Mas", 2=>"Centra Sarana Duta"))."<br>";
echo "Periode : ".form_dropdown("bln", array("01"=>"Januari", "01"=>"Februari", "01"=>"Maret", "01"=>"April", "01"=>"Mei", "01"=>"Juni", "01"=>"Juli", "01"=>"Agustus", "01"=>"September", "01"=>"Oktober", "01"=>"November", "01"=>"Desember")).
form_dropdown("thn", array('2001'=>'2010', '2001'=>'2011', '2001'=>'2012', '2001'=>'2013', '2001'=>'2014'))."<br><br>";
echo "<table class='tablesorter'>";
echo "<thead><tr><th>Jenis Layanan</th><th>Jml Tagihan</th><th>Hak Telkom (%)</th><th>Hak Telkom (Rp)</th><th>Hak Mitra (%)</th><th>Marketing Fee</th></tr></thead>";
echo "<tbody>";
echo "<tr><td>Abonemen</td><td>".form_input("jml_tagihan", 36000, "style='text-align:right'")."</td><td>".form_input("hak_telkom_prosen")." %</td><td>".form_input("hak_telkom_rp")."</td><td>".form_input("hak_mitra_prosen")." %</td><td>".form_input("hak_mitra_rp")."</td></tr>";
echo "<tr><td>Lokal</td><td>".form_input("jml_tagihan", 80000, "style='text-align:right'")."</td><td>".form_input("hak_telkom_prosen")." %</td><td>".form_input("hak_telkom_rp")."</td><td>".form_input("hak_mitra_prosen")." %</td><td>".form_input("hak_mitra_rp")."</td></tr>";
echo "<tr><td>SLJJ</td><td>".form_input("jml_tagihan", 90000, "style='text-align:right'")."</td><td>".form_input("hak_telkom_prosen")." %</td><td>".form_input("hak_telkom_rp")."</td><td>".form_input("hak_mitra_prosen")." %</td><td>".form_input("hak_mitra_rp")."</td></tr>";
echo "<tr><td>SLI007</td><td>".form_input("jml_tagihan", 120000, "style='text-align:right'")."</td><td>".form_input("hak_telkom_prosen")." %</td><td>".form_input("hak_telkom_rp")."</td><td>".form_input("hak_mitra_prosen")." %</td><td>".form_input("hak_mitra_rp")."</td></tr>";
echo "<tr><td colspan=5>Jml Marketing Fee sebelum PPN</td><td>".form_input("hak_mitra_rp")."</td></tr>";
echo "<tr><td colspan=5>PPN 10%</td><td>".form_input("hak_mitra_rp")."</td></tr>";
echo "<tr><td colspan=5><b>Jml Marketing Fee setelah PPN</b></td><td>".form_input("hak_mitra_rp")."</td></tr>";
echo "</tbody>";
echo "</table>";


?>
<input type='submit' name='submit' value='Submit' />