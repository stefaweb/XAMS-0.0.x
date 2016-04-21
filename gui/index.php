<?php
    require 'gfl.php';

    header('Content-Type: text/html; charset=UTF-8');
    header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
    header('Cache-Control: no-store, no-cache, must-revalidate');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
	
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    include_once 'include/config.php';

    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>";

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
<head>
    <title><?php echo _TITLE; ?></title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta http-equiv="Expires" content="-1" />
    <link rel="stylesheet" type="text/css" href="<?php echo _SKIN; ?>/css/xams.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo _SKIN; ?>/css/form.css" />
    <link rel="SHORTCUT ICON" href="favicon.ico" />
	<script type="text/javascript">
	// Function to control iframe height 
	function adjustIFrameSize (iframeWindow) {
		  if (iframeWindow.document.height) {
			var iframeElement = document.getElementById(iframeWindow.name);
			iframeElement.style.height = iframeWindow.document.height + 55 + 'px';
			//iframeElement.style.width = iframeWindow.document.width + 55 + 'px';
		  } else if (document.all) {
			var iframeElement = document.all[iframeWindow.name];
			if (iframeWindow.document.compatMode &&	iframeWindow.document.compatMode != 'BackCompat') {
			  iframeElement.style.height = iframeWindow.document.documentElement.scrollHeight + 55 + 'px';
			  //iframeElement.style.width = iframeWindow.document.documentElement.scrollWidth + 5 + 'px';
			}else {
			  iframeElement.style.height = iframeWindow.document.body.scrollHeight + 55 + 'px';
			  //iframeElement.style.width = iframeWindow.document.body.scrollWidth + 5 + 'px';
			}
		  }
		}
	</script>
	<?php
        if (isset($CSS_ADD))
            echo $CSS_ADD;
    ?>
	<style type="text/css">
		#container{
			width:990px;
			margin:0 auto;
		}
		
		#col_left{
			float:left;
			width:230px;
		}
		
		#col_right{
			width:748px;
			margin-left:10px;
			margin-top:0px;
			float:left;
		}
		
		#framemenu{
			height:900px;	
			width:230px;
			overflow:hidden;
		}
		
		#framecontenu{
			height:800px;	
			width:100%;
			overflow:auto;
		}
	</style>
</head>
<body>
	<div id="container">
		<div id="col_left">
        <iframe frameborder="0" name="framemenu" id="framemenu"  scrolling="no" src="menu.php">
        </iframe>
		</div>
		<div id="col_right">
         <iframe frameborder="0" name="framecontenu" id="framecontenu" src="startup.php">
        </iframe>
		</div>
	</div>
</body>
</html>
