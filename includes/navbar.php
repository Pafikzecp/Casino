
<div class="navbar-fixed z-depth-0">
    <nav>
        <div class="nav-wrapper">
            <a href="/index.php" class="left brand-logo">Casino</a>
            <ul id="nav-mobile" class="right">
                <?php if ($loggedIn): ?>
                    <li><a href="/account/account.php">Account</a></li>
                    <li><a href="/account/logout.php">Logout</a></li>
                <?php else: ?>
                    <li><a href="/account/login.php">Login</a></li>
                    <li><a href="/account/signup.php">Sign Up</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
</div>
