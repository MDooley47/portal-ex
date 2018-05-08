<?php
use SessionManager\Session;

if (Session::isActive()) : ?>
<a href="<?= $this->url('logout') ?>" class="post-noslug">Sign Out</a>
<? else : ?>
<a href="<?= $this->url('login') ?>">Sign In</a>
<? endif; ?>
