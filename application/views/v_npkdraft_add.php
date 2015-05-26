<div class='title'>Input Draft NPK</div>
<div class='sub_title'></div>
<?php

echo form_open("/npk/draftadddo");
echo "<table class='form'>";
echo "<tr><td>Pengelola</td><td>:</td><td>".form_dropdown("pgl_id", $pgl)."</td></tr>";
echo "<tr><td>Period</td><td>:</td><td>".form_dropdown("period_m", $this->M_npk->months)." ".form_dropdown("period_y", $this->M_npk->years, array(date("Y")) )."</td></tr>";
echo "<tr><td>Method</td><td>:</td><td>".form_dropdown("method", $this->M_npk->methods)."</td></tr>";
echo "<tr><td colspan=3><br><b><u>Telkom's Signer</u></b></td></tr>";
echo "<tr><td>Name</td><td>:</td><td>".form_input("sign_name_1", "", "size='35'")."</td></tr>";
echo "<tr><td>Position</td><td>:</td><td>".form_input("sign_pos_1", "", "size='35'")."</td></tr>";
echo "<tr><td colspan=3><br><b><u>Pengelola's Signer</u></b></td></tr>";
echo "<tr><td>Name</td><td>:</td><td>".form_input("sign_name_2", "", "size='35'")."</td></tr>";
echo "<tr><td>Position</td><td>:</td><td>".form_input("sign_pos_2", "", "size='35'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>