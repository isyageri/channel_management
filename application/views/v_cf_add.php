<div class='title'>Input Component Fee Type</div>
<div class='sub_title'></div>
<?php

echo form_open("/comp/typeadddo");
echo form_hidden("line_type", "POT");
echo "<table class='form'>";
echo "<tr><td>Component Name</td><td>:</td><td>".form_input("cf_name", "", "size='35'")."</td></tr>";
echo "<tr><td>Caption</td><td>:</td><td>".form_input("cf_caption", "", "size='35'")."</td></tr>";
echo "<tr><td>Source Type</td><td>:</td><td>".form_dropdown("cf_type", $this->M_compfee->cf_types)."</td></tr>";
//echo "<tr><td>Product/Service Type</td><td>:</td><td>".form_dropdown("line_type", $this->M_compfee->line_types)."</td></tr>";
echo "<tr><td>Formula String</td><td>:</td><td>".form_input("str_formula", "", "size='70'")."</td></tr>";

echo "<tr><td></td><td></td><td><input type='submit' name='submit' value='Submit' />&nbsp;&nbsp;<input type='submit' name='reset' value='Cancel' /></td></tr>";
echo "</table>";
echo form_close();
?>