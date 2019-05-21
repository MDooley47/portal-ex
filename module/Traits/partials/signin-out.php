<?php

use SessionManager\Session;

if (Session::isActive()) {
    // echo '<a href='.$this->url('logout').' class="post-noslug">Sign Out</a>';
} else {
    echo '<a href = '.$this->url('login').' > Sign In </a >';
}
