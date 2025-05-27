<?php

function base_url(string $path = ''): string
{
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $scriptDir = str_replace('/index.php', '', $scriptName);
    return $scriptDir . '/' . ltrim($path, '/');
}