<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?= self::title(); ?></title>
    <link href='https://fonts.googleapis.com/css?family=Raleway' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/css/style.css"/>
</head>
<body>
<header>
    <div class="limiter">
        <nav id="mainMenu">
            <ul>
                <li><a href="/">Home</a></li>
                <li><a href="/categories/view">Categories</a></li>
                <li><a href="/users/myproducts">My Products</a></li>
                <?php if (!self::logged()) { ?>
                    <li><a href="/login">Login</a></li>
                    <li><a href="/register">Register</a></li>
                <?php } else { ?>
                    <li><a href="/profile">Profile</a></li>
                    <?php if (self::role() == 3) : ?>
                        <li><a href="/admin/products">Admin panel</a></li>
                    <?php endif; ?>
                    <li><a href="/cart/view">Cart</a></li>
                    <li><a href="/logout">Logout</a></li>
                <?php } ?>
            </ul>
        </nav>
    </div>
</header>
<main>
    <div class="limiter">