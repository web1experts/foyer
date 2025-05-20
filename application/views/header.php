<!DOCTYPE html>
<html>
<head>
	<?php if(isset($site_title)){ ?>
		<title><?= $site_title; ?></title>
	<?php } else { ?>
		<title>Foyer | Hagan Realty</title>
	<?php } ?>
   <!--Made with love by Mutiullah Samim -->
   
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<!--Bootsrap 4 CDN-->
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
    
    <!--Fontawesome CDN-->
	<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.3.1/css/all.css">

	<!--Custom styles-->
	<link rel="stylesheet" type="text/css" href="<?=base_url('assets/style.css');?>">

	<link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">	
	<script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>

	<script>
		$(function(){
			$(".manual_login").click(function(){
				if($(this).hasClass("active")){
					$(this).html("Gmail")
					$(this).removeClass("active");
					$(".card_gmail").hide();
					$(".card_email .collapse").fadeIn("slow");					
				} else {
					$(this).html("Manual")
					$(this).addClass("active");
					$(".card_email .collapse").hide();
					$(".card_gmail").fadeIn("slow");					
				}
			});
		});
	</script>
</head>

<?php 
$background=base_url(). "assets/images/login_bg.jpg";
if(isset($login_background) && !empty($login_background)){
 	$background=base_url()."assets/admin/upload/settings/".$login_background->login_image;
}?>

<body class="login" style="background-image:url(<?php echo $background; ?>)">