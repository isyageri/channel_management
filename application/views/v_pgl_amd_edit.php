<div class='title'>Edit Amandemen</div>
<?php
echo "<div class='sub_title'>".$pgl[0]->PGL_NAME."</div>";
echo "<div class='sub_title'>".$mou_no."</div>";
echo form_open_multipart("/pgl/amdeditdo");
echo form_hidden("pgl_id", $pgl[0]->PGL_ID);
echo form_hidden("mou_no", $mou_no);
echo form_hidden("amd_no", $amd_no);
echo "<table class='form'>";
echo "<tr><td>AMD No</td><td>:</td><td>".$dt[0]->AMD_NO."</td></tr>";
echo "<tr><td>Date</td><td>:</td><td>".form_input("amd_date", $dt[0]->AMD_DATE, "size='10' class='datepicker'")." (dd/mm/yyyy)</td></tr>";
echo "<tr><td>Description</td><td>:</td><td>".form_input("amd_desc", $dt[0]->AMD_DESC, "size='50'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>