<?php $rebrand = load_config(); ?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="description" content="<?php echo $rebrand['meta_description'] ?>">
	<meta name="keywords" content="<?php echo $rebrand['meta_keywords'] ?>">
	<link rel="shortcut icon" href="<?php echo base_url('assets/img/images/'.$rebrand['favicon_icon'].''); ?>">
	<title>
		<?php echo lang('loginsystem')?>
	</title>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/material-design-icons/css/material-design-iconic-font.min.css"/>
	<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/lib/jquery.gritter/css/jquery.gritter.css"/>
	<link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/ciuis.css" type="text/css"/>
</head>
<div class="col-lg-4 col-lg-offset-4">
	<div class="col-lg-7 text-center col-lg-offset-3 bg-white" style="padding: 20px; border-radius: 5px; margin-top: 5%;" >
		<?php echo "<img width='100%' src='" . $url_qr_code . "' /> \n"; ?>
		<?php echo form_open(base_url('login/verify_login')) ?>
		<h3><?php echo lang('two_factor_authentication')?></h3>
		<small><?php echo lang('two_factor_authentication_description_verify')?></small>
		<div class="input-group md-mt-5">
			<input type="text" name="code" placeholder="<?php echo lang('type_your_verification_code') ?>" class="form-control"><span class="input-group-btn">
            <button  type="submit" class="btn btn-success"><?php echo lang('verify')?></button></span>
		</div>
		<input type="hidden" value="<?php echo $secret; ?>" name="secret_code">
		</form>
	</div>
</div>
</body>
</html>