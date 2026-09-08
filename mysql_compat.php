<?php
// Compatibility layer: provide mysql_* wrappers that call mysqli equivalents using $conn when possible
// This helps old code continue working while we migrate files gradually.

if (!function_exists('mysql_connect')) {
    function mysql_connect($host = null, $user = null, $pass = null, $new_link = false, $client_flags = 0)
    {
        // Try to create a mysqli connection and return the mysqli object
        $link = @mysqli_connect($host, $user, $pass);
        return $link;
    }
}

if (!function_exists('mysql_select_db')) {
    function mysql_select_db($dbname, $link = null)
    {
        global $conn;
        if ($link === null) $link = $conn;
        return mysqli_select_db($link, $dbname);
    }
}

if (!function_exists('mysql_query')) {
    function mysql_query($query, $link = null)
    {
        global $conn;
        if ($link === null) $link = $conn;
        return mysqli_query($link, $query);
    }
}

if (!function_exists('mysql_fetch_array')) {
    function mysql_fetch_array($result)
    {
        return mysqli_fetch_array($result, MYSQLI_ASSOC);
    }
}

if (!function_exists('mysql_fetch_assoc')) {
    function mysql_fetch_assoc($result)
    {
        return mysqli_fetch_assoc($result);
    }
}

if (!function_exists('mysql_fetch_row')) {
    function mysql_fetch_row($result)
    {
        return mysqli_fetch_row($result);
    }
}

if (!function_exists('mysql_num_rows')) {
    function mysql_num_rows($result)
    {
        return mysqli_num_rows($result);
    }
}

if (!function_exists('mysql_num_fields')) {
    function mysql_num_fields($result)
    {
        return mysqli_num_fields($result);
    }
}

if (!function_exists('mysql_real_escape_string')) {
    function mysql_real_escape_string($unescaped_string, $link = null)
    {
        global $conn;
        if ($link === null) $link = $conn;
        return mysqli_real_escape_string($link, $unescaped_string);
    }
}

if (!function_exists('mysql_error')) {
    function mysql_error($link = null)
    {
        global $conn;
        if ($link === null) $link = $conn;
        return mysqli_error($link);
    }
}

if (!function_exists('mysql_insert_id')) {
    function mysql_insert_id($link = null)
    {
        global $conn;
        if ($link === null) $link = $conn;
        return mysqli_insert_id($link);
    }
}

if (!function_exists('mysql_close')) {
    function mysql_close($link = null)
    {
        global $conn;
        if ($link === null) $link = $conn;
        return mysqli_close($link);
    }
}

// Note: This is a shim to minimize edits. Prefer migrating code to use mysqli/PDO and prepared statements.
