<html>

<head>
	<title><?php echo($pageTitle);?></title>
	<meta charset="utf-8">
  	<meta name="viewport" content="width=device-width, initial-scale=1">

  	<link href="css/bootstrap-3.3.4.min.css" rel="stylesheet">
	<link href="css/jquery-ui.css" rel="stylesheet">
	<link href="css/jquery-ui.structure.css" rel="stylesheet">
	<link href="css/jquery-ui.structure.min.css" rel="stylesheet">
	<link href="css/jquery-ui.theme.css" rel="stylesheet">
	<link href="css/jquery-ui.theme.min.css" rel="stylesheet">
	<link href="css/jquery-ui.min.css" rel="stylesheet">
  	<link href="css/levelstylesheet.css" rel="stylesheet" type="text/css"/>
	<link href="css/practicebuttons.css" rel="stylesheet">
  	<script src="js/jquery-1.11.1.min.js" type="text/javascript"></script>
	<script src="js/jquery-ui.js" type="text/javascript"></script>
  	<script src="js/bootstrap-3.2.0.min.js" type="text/javascript"></script>
	<script src="js/html5shiv-3.6.2.js" type="text/javascript"></script>
	<script src="js/dental.js" type="text/javascript"></script>
	<link href="css/mainstyle.css" rel="stylesheet">

	<!-- BUGHERD Sidebar implementation  -->
	<script type='text/javascript'>
		(function (d, t) {
		var bh = d.createElement(t), s = d.getElementsByTagName(t)[0];
		bh.type = 'text/javascript';
		bh.src = '//www.bugherd.com/sidebarv2.js?apikey=mppfalf1wvw4illgwharkg';
		s.parentNode.insertBefore(bh, s);
		})(document, 'script');
	</script>

	<!--[if lt IE 9]>
	<script src"http://html5shiv.googlecode.com/svn/trunk/html5.js">
	</script>
	<![endif]-->

	<script>
     $(document).ready(function(){
        $('.dropdown-toggle').dropdown()
    });
	</script>

</head>
	<!-- This navagation bar is a modified template found here http://bootswatch.com/, it will enable users to easily navigate the game site -->


	<div class="navbar hidden-xs">
		<div class="container">
			<div id="logo-container">
				<img id="logo-nav" src="css/images/logo-nav.png" alt="logo" width="85px" />
			</div>
			<div class="navmenu">
				<button class="navlink" onclick="window.location = 'gameattempt.php';" id="play"> Play</button>
				<button class="navlink" onclick="window.open('http://www.oralpathologyatlas.net');"> Atlas </button>
				<button class="navlink" onclick="window.location = 'homepage.php';"> Leader Board </button>
				<button class="navlink" onclick="window.location = 'practice.php';"> Practice </button>
				<button class="navlink" onclick="window.location = 'tutorial.php';"> Instructions </button>
				<button class="navlink" onclick="window.location = 'homepage.php';"> Home </button>
			</div>
		</div>
	</div>

	<!-- XS -->
	<div class="hidden-lg hidden-md hidden-sm col-xs-12">
	 <div class="mobile-navbar">
		 <!-- logo -->
		<div class=" mobile-logo-container">
			<img id="mobile-logo-nav" src="css/images/logo-nav.png" alt="Logo" />
		</div>

		<button class="menu-icon btn btn-primary dropdown-toggle" type="button" data-toggle="dropdown">
		<img src="css/images/menu-icon.gif" alt="menu-icon" width="20px;"></button>

		<div class="mobile-navmenu">
			<ul class="dropdown-menu" role="menu" aria-labelledby="menu1">
				<li role="presentation"><a role="menuitem" tabindex="-1" href="homepage.php">Home</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="about.html">About</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="graphics.html">Graphics</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="web.html">Web</a></li>
				<li role="presentation" class="divider"></li>
				<li role="presentation"><a role="menuitem" tabindex="-1" href="art.html">Art</a></li>
			</ul>
		</div>
	</div>
</div>
