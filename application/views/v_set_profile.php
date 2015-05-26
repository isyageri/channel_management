<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>.: Login :.</title>
</head>
<body>
<?=form_open('home/setprofiledo')?>
 <table width="580" height="430" align="center" cellpadding="0" cellspacing="0" style="border: 1px solid #1B305D; padding: 20px;">
    <tr><td align='right'><img src="<?=image_asset_url("logo_telkom.png"); ?>" /></td></tr>
	<tr>
      <td width="574"><table width="250px" border="0">

          <tr>
            <td width="72px"><font color="#000066" size="4"><i><b></b></i></font></td>
            <td><font color="#000066" size="4"><i><b>.: Set Profile :.</b></i></font></td>
          </tr>
		  <tr>
            <td>&nbsp;</td>
            <td>&nbsp;</td>

          </tr>
          <tr>
            <td><font face="Verdana, Arial, Helvetica, sans-serif" color="#000066"><?=form_label('NIK', 'user_name')?></font></td>
            <td><b><?=$this->session->userdata("d_nik")?></b></td>
          </tr>
          <tr>
            <td><font color="#000066"><font face="Verdana, Arial, Helvetica, sans-serif" color="#000066"><?=form_label('Profiles', 'prof_id')?></font></td>
            <td><?=form_dropdown('prof_id', $profs)?> </td>
		   </tr>
          <tr>
            <td>&nbsp;</td>
            <td><div align="right">
             <?=form_submit('login', 'Enter Profile')?>
            </div></td>
          </tr>
		  <tr>
           <td colspan=2><div align="right">
				 <?=form_error('user_name')?>
				 <?=form_error('user_pass')?>
				<?php if($this->session->userdata('d_message')) : ?>
					<p><?=$this->session->userdata('d_message')?></p>
				<?php endif; ?>

            </div></td>
          </tr>
      </table>
	  </td>
    </tr>

  </table>


<?=form_close();?>
</body>
</html>