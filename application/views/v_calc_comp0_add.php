<div class='title'>Add Common Component Fee</div>

<?php
echo "<div class='sub_title'>NPK : <b>".$pgl[0]->PGL_NAME."</b></div>";
echo "<div class='sub_title'>Period : <b>".$dt[0]->PERIOD."</b></div>";
echo "<div class='sub_title'>Method : <b>".$this->M_npk->methods[$dt[0]->METHOD]."</b></div>";
echo "<br><br>";

echo form_open("/calc/addcomp0do/".$npk_id);
echo form_hidden("npk_id", $npk_id);
echo "<table class='form'>";
echo "<tr><td>Component Fee</td><td>:</td><td>".form_dropdown("cf_id", $compfee)."</td></tr>";
echo "<tr><td>Nominal</td><td>:</td><td>".form_input("cf_nom", "", "size=30")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();

?>