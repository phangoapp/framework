<?php

function HomeView($title, $content)
{

?>
<!DOCTYPE html>
<html>
	<head>
	<title><?php echo $title; ?></title>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8">
	<?php 
		echo View::loadJS();
		echo View::loadCSS();
		echo View::loadHeader();
	?>
	</head>
	<body>
		<?php echo $content; ?>
	</body>
</html>

<?php

}

?>