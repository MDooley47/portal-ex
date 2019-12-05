<?php

use SessionManager\Session;

if (Session::isActive()) {
    echo '<a href="'.$this->url('logout').'">Sign Out</a>';
} else {
    echo '<a href="'.$this->url('login').'"> Sign In </a >';
}
