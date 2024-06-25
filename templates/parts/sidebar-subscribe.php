<?php

if (is_active_sidebar('blog-subscribe') && function_exists('dynamic_sidebar')) {
    dynamic_sidebar('blog-subscribe');
}
