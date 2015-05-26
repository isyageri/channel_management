<div class='title'>BA Document > Set No. PKS<span style='display:inline;float:right'><a href='<?=site_url("/doc/ba"); ?>'><< back</a></span></div>
<div class='sub_title'></div>
<?php

echo form_open("/doc/basetmoudo", "id='form-mou'");
echo form_hidden("npk_id", $dt[0]->NPK_ID);
echo "<table class='form'>";
echo "<tr><td>Pengelola</td><td>:</td><td>".$pgl[ $dt[0]->PGL_ID ]."</td></tr>";
echo "<tr><td>Period</td><td>:</td><td>".$dt[0]->PERIOD."</td></tr>";
echo "<tr><td>Method</td><td>:</td><td>".$this->M_npk->methods[$dt[0]->METHOD]."</td></tr>";
echo "<tr><td colspan=3><div id='error-1' class='error-div'></div></td></tr>";
echo "<tr><td colspan=3><br><b><u>Introductory Sheet</u></b></td></tr>";
echo "<tr><td>AM Name</td><td>:</td><td>".form_input("am_name", (isset($ba[0])?$ba[0]->AM_NAME:""), "size='35' class='req-string'")."</td></tr>";
echo "<tr><td>AM Position</td><td>:</td><td>".form_input("am_pos", (isset($ba[0])?$ba[0]->AM_POS:""), "size='30' class='req-string'")."</td></tr>";
echo "<tr><td>Source of Data</td><td>:</td><td>".form_input("data_source", (isset($ba[0])?$ba[0]->DATA_SOURCE:""), "size='30' class='req-string'")."</td></tr>";
echo "<tr><td>Signer Name (UBC)</td><td>:</td><td>".form_input("ubc_signer_name", (isset($ba[0])?$ba[0]->UBC_SIGNER_NAME:"AMDJAD AGOES"), "size='35' class='req-string'")."</td></tr>";
echo "<tr><td>Signer NIK (UBC)</td><td>:</td><td>".form_input("ubc_signer_nik", (isset($ba[0])?$ba[0]->UBC_SIGNER_NIK:"620725"), "size='10' class='req-string'")."</td></tr>";
echo "<tr><td>Signer Position (UBC)</td><td>:</td><td>".form_input("ubc_signer_pos", (isset($ba[0])?$ba[0]->UBC_SIGNER_POS:"OM BILLING & COLLECTION AREA II DIVES"), "size='35' class='req-string'")."</td></tr>";
echo "<tr><td>Signer Name (GS)</td><td>:</td><td>".form_input("gs_signer_name", (isset($ba[0])?$ba[0]->GS_SIGNER_NAME:""), "size='35' class='req-string'")."</td></tr>";
echo "<tr><td>Signer NIK (GS)</td><td>:</td><td>".form_input("gs_signer_nik", (isset($ba[0])?$ba[0]->GS_SIGNER_NIK:""), "size='10' class='req-string'")."</td></tr>";
echo "<tr><td>Signer Position (GS)</td><td>:</td><td>".form_input("gs_signer_pos", (isset($ba[0])?$ba[0]->GS_SIGNER_POS:""), "size='35' class='req-string'")."</td></tr>";
echo "<tr><td colspan=3><br><b><u>PKS</u></b></td></tr>";
echo "<tr><td>No. PKS</td><td>:</td><td>".form_input("mou_no", $dt[0]->MOU_NO, "size='35' class='req-string'")."</td></tr>";
echo "<tr><td>Date of PKS</td><td>:</td><td>".form_input("mou_date", $dt[0]->MOU_DATE, "size='10' class='datepicker req-string'")." (dd/mm/yyyy)</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' id='submit-mou' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>