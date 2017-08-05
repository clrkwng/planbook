<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Edit profile page</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Theme CSS -->
    <link href="../../css/start-bootstrap-template.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="../../libs/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="../../css/user/user-profile.css" rel="stylesheet" type="text/css">

    <!-- Custom Fonts -->
    <link href="//fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">
    <link href="//fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic" rel="stylesheet" type="text/css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css" rel="stylesheet">

</head>
<body id="page-top" class="index">

<!-- Navigation -->
<nav id="mainNav" class="navbar navbar-default navbar-fixed-top navbar-custom">
    <div class="container">
        <!-- Brand and toggle get grouped for better mobile display -->
        <div class="navbar-header page-scroll">
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                <span class="sr-only">Toggle navigation</span> Menu <i class="fa fa-bars"></i>
            </button>
            <a class="navbar-brand" href="#page-top">Planbook</a>
        </div>

        <!-- Collect the nav links, forms, and other content for toggling -->
        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right">
                <li class="hidden">
                    <a href="#page-top"></a>
                </li>
                <li>
                    <a href ="../user/UserProfile.php">Profile</a>
                </li>
                <li class="page-scroll">
                    <a href="#profile-settings">Profile Settings</a>
                </li>
                <li role="separator" class="divider"></li>
                <li>
                    <a href="../auth/Login.php">Log out</a>
                </li>
            </ul>
        </div>
        <!-- /.navbar-collapse -->
    </div>
    <!-- /.container-fluid -->
</nav>

<!-- Header -->
<header>
    <div class="container" id="maincontent" tabindex="-1">
        <div class="row">
            <div class="col-lg-12">
                <div class="intro-text">
                    <h1 class="name">Profile Settings</h1>
                    <hr class="star-light">
                </div>
            </div>
        </div>
    </div>
</header>

<section id="profile-settings">
    <div class="container">

    <div class="row">
        <div class="col-xs-12 col-sm-9">
            <form class="form-horizontal">

                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h4 class="panel-title">Contact info</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Phone number</label>
                            <div class="col-sm-10">
                                <input type="tel" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Email address</label>
                            <div class="col-sm-10">
                                <input type="email" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="panel-heading">
                        <h4 class="panel-title">Security</h4>
                    </div>
                    <div class="panel-body">
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Old password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">New password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-sm-2 control-label">Re-enter password</label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control">
                            </div>
                        </div>
                        <div class="form-group">
                            <div class="col-sm-10 col-sm-offset-2">
                                <button type="submit" class="btn btn-primary">Submit</button>
                                <button type="reset" class="btn btn-default">Cancel</button>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

        </div>
        <div class="col-xs-12 col-sm-3">

            <div class="profile__contact-info">
                <div class="profile__contact-info-item">
                    <div class="profile__contact-info-icon">
                        <i class="fa fa-comment"></i>
                    </div>
                    <div class="profile__contact-info-body">
                        <h5 class="profile__contact-info-heading">
                            Quick tip
                        </h5>
                        Never share your password with anyone besides your parents!
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
</section>
<!-- Footer -->
<footer class="text-center">
    <div class="footer-above">
        <div class="container">
            <div class="row">
                <div class="footer-col col-md-4">
                    <h3>Location</h3>
                    <p>400 Cedar Ave
                        <br>West Long Branch, NJ 07764</p>
                </div>
                <div class="footer-col col-md-4">
                    <h3>Around the Web</h3>
                    <ul class="list-inline">
                        <li>
                            <a href="#" class="btn-social btn-outline"><span class="sr-only">Facebook</span><i class="fa fa-fw fa-facebook"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn-social btn-outline"><span class="sr-only">Google Plus</span><i class="fa fa-fw fa-google-plus"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn-social btn-outline"><span class="sr-only">Twitter</span><i class="fa fa-fw fa-twitter"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn-social btn-outline"><span class="sr-only">Linked In</span><i class="fa fa-fw fa-linkedin"></i></a>
                        </li>
                        <li>
                            <a href="#" class="btn-social btn-outline"><span class="sr-only">Dribble</span><i class="fa fa-fw fa-dribbble"></i></a>
                        </li>
                    </ul>
                </div>
                <div class="footer-col col-md-4">
                    <h3>About Planbook</h3>
                    <p>Planbook is a free to use tool to help keep lives organized.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="footer-below">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    Copyright &copy; Planbook 2017
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Scroll to Top Button (Only visible on small and extra-small screen sizes) -->
<div class="scroll-top page-scroll hidden-sm hidden-xs hidden-lg hidden-md">
    <a class="btn btn-primary" href="#page-top">
        <i class="fa fa-chevron-up"></i>
    </a>
</div>

<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]-->
<script src="../../libs/html5shiv/dist/html5shiv.min.js"></script>
<script src="../../libs/vendor/respond.min.js"></script>

<!-- jQuery -->
<script src="../../libs/jquery/dist/jquery.min.js"></script>

<!-- Bootstrap Core JavaScript -->
<script src="../../libs/bootstrap/dist/js/bootstrap.min.js"></script>

<!-- Plugin JavaScript -->
<script src="../../libs/jquery-easing/jquery.easing.min.js"></script>
<!-- Theme JavaScript -->
<script src="../../libs/freelancer/dist/freelancer.js"></script>

<!-- Fonts -->
<script src="//use.fontawesome.com/7d70b9fab6.js"></script>


</body>
</html>