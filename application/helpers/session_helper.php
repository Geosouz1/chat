<?php

function checkSession()
{
    $CI = & get_instance();
    $session = $CI->session->userdata('login_status');
    if ($session!=='ok') {
        redirect('login');
    } 
}

function checkLoginSession()
{
    $CI = & get_instance();
    $session = $CI->session->userdata('login_status');
    if ($session=='ok') {
        redirect('principal');
    } 
}
