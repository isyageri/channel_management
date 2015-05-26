<div class='title'>Edit Component Fee Type</div>
<div class='sub_title'></div>
<?php

echo form_open("/comp/typeeditdo");
echo form_hidden("cf_id", $dt[0]->CF_ID);
echo form_hidden("cf_name", $dt[0]->CF_NAME);
echo form_hidden("line_type", $dt[0]->LINE_TYPE);
echo "<table class='form'>";
echo "<tr><td>Component Name</td><td>:</td><td>".$dt[0]->CF_NAME."</td></tr>";
echo "<tr><td>Caption</td><td>:</td><td>".form_input("cf_caption", $dt[0]->CF_CAPTION, "size='35'")."</td></tr>";
echo "<tr><td>Source Type</td><td>:</td><td>".form_dropdown("cf_type", $this->M_compfee->cf_types, array($dt[0]->CF_TYPE))."</td></tr>";
//echo "<tr><td>Product/Service Type</td><td>:</td><td>".form_dropdown("line_type", $this->M_compfee->line_types, array($dt[0]->LINE_TYPE))."</td></tr>";
echo "<tr><td>Formula String</td><td>:</td><td>".form_input("str_formula", $dt[0]->STR_FORMULA, "size='70'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>