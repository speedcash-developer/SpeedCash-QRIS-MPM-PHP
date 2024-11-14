<?php

function dateTime() {
    $dateTime = new DateTime('now', new DateTimeZone('Asia/Jakarta'));
    return $dateTime->format(DateTime::ATOM);
}

function generateToken($length) {
    $token = bin2hex(random_bytes($length)); 
    return $token;
}
