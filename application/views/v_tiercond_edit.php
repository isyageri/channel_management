<div class='title'>Edit Tier's Conditional</div>
<div class='sub_title'></div>
<?php

echo form_open("/comp/tiercondeditdo/".$tier[0]->TIER_ID);
echo form_hidden("tier_id", $tier[0]->TIER_ID);
echo form_hidden("seq_no", $dt[0]->SEQ_NO);
echo "<table class='form'>";
echo "<tr><td>Tier</td><td>:</td><td>{".$tier[0]->TIER_NAME."}</td></tr>";
echo "<tr><td>Seq.</td><td>:</td><td>".$dt[0]->SEQ_NO."</td></tr>";
echo "<tr><td>Conditional Logic</td><td>:</td><td>".form_input("str_cond", $dt[0]->STR_COND, "size='35'")."</td></tr>";
echo "<tr><td>Result</td><td>:</td><td>".form_input("nresult", $dt[0]->NRESULT, "size='10'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>