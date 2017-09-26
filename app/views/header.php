<!DOCTYPE HTML>
<html>
    <head>
    <?php $url = "/02/UltrafastLaser";?>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
        <meta name="description" content="notebook">
        <meta name="author" content="">
        <link rel="icon" href="<?php echo $url;?>/app/views/favicon.ico">

        <title>Ulrafast Laser Physics</title>

        <!-- Bootstrap core CSS -->
        <link href="<?php echo $url;?>/app/views/bootstrap/css/bootstrap.min.css" rel="stylesheet">

        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <link href="<?php echo $url;?>/app/views/bootstrap/css/ie10-viewport-bug-workaround.css" rel="stylesheet">

        <!-- Custom styles for this template -->
        <link href="<?php echo $url;?>/app/views/bootstrap/examples/starter-template/starter-template.css" rel="stylesheet">
        
        <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
          <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="./bootstrap/docs/assets/js/vendor/jquery.min.js"><\/script>')</script>
        <script src="<?php echo $url;?>/app/views/bootstrap/js/bootstrap.min.js"></script>
        <!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
        <script src="<?php echo $url;?>/app/views/bootstrap/js/ie10-viewport-bug-workaround.js"></script>
        <link href="<?php echo $url;?>/app/views/css/main.css" rel="stylesheet">
    
    </head>

    <body>

        <nav class="navbar navbar-inverse navbar-fixed-top" >
          <div class="container">
            <div class="navbar-header">
              <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="<?php echo $url;?>/public/home">Ultrafast Laser Physics</a>
            </div>
            <div id="navbar" class="collapse navbar-collapse">
              <ul class="nav navbar-nav">
              
              	<li class="dropdown active">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Theory<b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li><a href="<?php echo $url;?>/public/theory/lasertheory/superradiance">Superradiance</a></li>
                <li class="divider"></li>
                <li><a href="<?php echo $url;?>/public/theory/lasertheory/duration">Pulse Duration</a></li>
              </ul>
            </li>
              
                <li class="active"><a href="<?php echo $url;?>/public/news/lasernews">News</a></li>
                <li class="active"><a href="<?php echo $url;?>/public/registration/newuserregistration">Registration</a></li>
                <?php if (isset($_SESSION['user'])) {?>
                <li class="active"><a href="<?php echo $url;?>/public/auth/authuser&act=user_exit">Log out</a></li>
                
                <?php } else {?>
                	<li class="active"><a href="<?php echo $url;?>/public/auth/authuser">Log in</a></li>  
                <?php }?>         
              </ul>
            </div><!--/.nav-collapse -->
            
          </div>
        </nav>

        <div class="container">
            <h2 class="text-center" style="margin: 30px;">
                <strong>Физика ультракоротких лазерных импульсов</strong>
            </h2>
    