<div class='title'>Input Tier's Conditional</div>
<div class='sub_title'></div>
<?php

echo form_open("/comp/tiercondadddo/".$tier[0]->TIER_ID);
echo form_hidden("tier_id", $tier[0]->TIER_ID);
echo "<table class='form'>";
echo "<tr><td>Tier</td><td>:</td><td>{".$tier[0]->TIER_NAME."}</td></tr>";
echo "<tr><td>Seq.</td><td>:</td><td>".form_input("seq_no", "", "size='2'")."</td></tr>";
echo "<tr><td>Conditional Logic</td><td>:</td><td>".form_input("str_cond", "", "size='35'")."</td></tr>";
echo "<tr><td>Result</td><td>:</td><td>".form_input("nresult", "", "size='10'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>