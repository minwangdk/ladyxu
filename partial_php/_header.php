<?php
session_start();

header('Content-Type: text/html; charset=utf-8'); 

// no cache
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header( 'Expires: Fri, 01 Jan 2010 00:00:00 GMT' );