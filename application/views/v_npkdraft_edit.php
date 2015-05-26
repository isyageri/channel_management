<div class='title'>Edit Draft NPK</div>
<div class='sub_title'></div>
<?php

echo form_open("/npk/drafteditdo");
echo form_hidden("npk_id", $dt[0]->NPK_ID);
echo form_hidden("pgl_id", $dt[0]->PGL_ID);
echo form_hidden("period", $dt[0]->PERIOD);
echo "<table class='form'>";
echo "<tr><td>Pengelola</td><td>:</td><td>".$pgl[ $dt[0]->PGL_ID ]."</td></tr>";
echo "<tr><td>Period</td><td>:</td><td>".$dt[0]->PERIOD."</td></tr>";
echo "<tr><td>Method</td><td>:</td><td>".form_dropdown("method", $this->M_npk->methods, array($dt[0]->METHOD))."</td></tr>";
echo "<tr><td colspan=3><br><b><u>Telkom's Signer</u></b></td></tr>";
echo "<tr><td>Name</td><td>:</td><td>".form_input("sign_name_1", $dt[0]->SIGN_NAME_1, "size='35'")."</td></tr>";
echo "<tr><td>Position</td><td>:</td><td>".form_input("sign_pos_1", $dt[0]->SIGN_POS_1, "size='35'")."</td></tr>";
echo "<tr><td colspan=3><br><b><u>Pengelola's Signer</u></b></td></tr>";
echo "<tr><td>Name</td><td>:</td><td>".form_input("sign_name_2", $dt[0]->SIGN_NAME_2, "size='35'")."</td></tr>";
echo "<tr><td>Position</td><td>:</td><td>".form_input("sign_pos_2", $dt[0]->SIGN_POS_2, "size='35'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>