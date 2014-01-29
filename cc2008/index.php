<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xmlns:og="http://opengraphprotocol.org/schema/">
<head>
	<title>CC2008</title>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	
	<style type="text/css">
		body{
			 color: #333333;
			direction: ltr;
			font-family: 'lucida grande',tahoma,verdana,arial,sans-serif;
			font-size: 11px;
			line-height: 1.28;
			text-align: left;
		}
		
		
		.item{
			width:160px;
			float:left
			height:250px;
		}
		
		.item label{
			color:#303030;
			font-weight:bold;
			display:block;
			margin-bottmo:6px;
			font-size:13px;
		}
		.item .img-principal{
			margin-bottom:6px;
		}
		.item i{
			font-weight:bold;
			font-size:10px;
			color:#232A51;
		}
		
		.item i img{
			float:left;
			margin-right:6px;
		}
	</style>
</head>
<body>
<?php
	$json = file_get_contents('https://api.instagram.com/v1/tags/cc2008/media/recent?client_id=350706e91fae465594f196d13a49105b');
	$objs = json_decode($json);
	foreach($objs->data as $foto)
	{
?>
	<div class="item">
		<label><?php echo $foto->caption->text; ?></label>
		<img class="img-principal" width="150" height="150" src="<?php echo $foto->images->thumbnail->url ?>" />
		<i><img width="15" height="15" src="<?php echo $foto->user->profile_picture; ?>" /><?php echo $foto->user->username; ?></i>
		
	</div>
<?php
	}
?>
</body>
</html>