<div class='title'>Save Formula of NPK</div>
<div class='sub_title'></div>
<?php

echo form_open("/npk/savedo/".$dt[0]->NPK_ID);
echo form_hidden("npk_id", $dt[0]->NPK_ID);
echo "<table class='form'>";
echo "<tr><td>NPK ID</td><td>:</td><td>".$dt[0]->NPK_ID."</td></tr>";
echo "<tr><td>Pengelola</td><td>:</td><td>".$pgl[0]->PGL_NAME."</td></tr>";
echo "<tr><td>Period</td><td>:</td><td>".$dt[0]->PERIOD."</td></tr>";
echo "<tr><td>Method</td><td>:</td><td>".$this->M_npk->methods[$dt[0]->METHOD]."</td></tr>";
echo "<tr><td>Save As Name</td><td>:</td><td>".form_input("npk_rc_name", "", "size='35'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>