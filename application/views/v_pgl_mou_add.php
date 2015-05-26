<div class='title'>Input PKS</div>
<?php
echo "<div class='sub_title'>".$pgl[0]->PGL_NAME."</div>";
echo form_open_multipart("/pgl/mouadddo");
echo form_hidden("pgl_id", $pgl[0]->PGL_ID);
echo "<table class='form'>";
echo "<tr><td>PKS No</td><td>:</td><td>".form_input("mou_no", "", "size='35'")."</td></tr>";
echo "<tr><td>Date</td><td>:</td><td>".form_input("start_date", "", "size='10' class='datepicker'")." - ".form_input("end_date", "", "size='10' class='datepicker'")." (dd/mm/yyyy)</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>