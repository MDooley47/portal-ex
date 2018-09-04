<?php
use SessionManager\Session;

if (Session::isActive()) {
    print "<a href=" . $this->url('logout') . " class=\"post-noslug\">Sign Out</a>";
} else {
    print "<a href = " . $this->url('login') . " > Sign In </a >";
}
?>