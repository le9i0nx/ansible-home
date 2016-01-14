<?php

function forbidden() {
    error_log("forbidden: " . $_SERVER['REMOTE_ADDR'] . ', user: ' . $_SERVER['PHP_AUTH_USER']);
    // avoid brute force attacks
    sleep(rand(0, 3));
    // re-display login form
    session_destroy();
    // don't give too much info (e.g. user does not exist / password is wrong)
    Header("HTTP/1.0 403 Forbidden");
    die('Unauthorized.');
}
function authenticate() {
    error_log("authreq: " . $_SERVER['REMOTE_ADDR']);
    // mark that we saw the login box.
    $_SESSION['AUTH'] = 1;
    // browser shows login box
    Header("WWW-Authenticate: Basic realm=Vvedite login i parol kak na rabochem komputere");
    Header("HTTP/1.0 401 Unauthorized");
    die('Unauthorized.');
}
function ldap_auth() {
    $ldap_server = 'ldap://127.0.0.1/';
    $ldap_domain = 'dc=rugion,dc=ru';
    //$ldap_userbase = 'ou=users,ou=chelyabinsk,' . $ldap_domain;
    //$ldap_user = 'uid=' . $_SERVER['PHP_AUTH_USER'] . ',' . $ldap_userbase;
    $ldap_user = ' ';
    $ldap_pass = $_SERVER['PHP_AUTH_PW'];

    $ldapconn_s = ldap_connect($ldap_server)
        or die("Could not connect to LDAP server.");
    ldap_set_option($ldapconn_s, LDAP_OPT_PROTOCOL_VERSION, 3) ;
    if ($ldapconn_s) {
		$ldapbind_s = @ldap_bind($ldapconn_s);
		$result = ldap_search($ldapconn_s,$ldap_domain, "(&(uid=". $_SERVER['PHP_AUTH_USER'] .")(objectClass=sambaSamAccount)(!(sambaAcctFlags=[DU ])))");
		$info = ldap_get_entries($ldapconn_s, $result);
		$ldap_user = $info[0]["dn"];
    }
    ldap_close($ldapconn_s);
    // connect to ldap server
    $ldapconn = ldap_connect($ldap_server)
        or die("Could not connect to LDAP server.");
    ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3) ;
    if ($ldapconn) {
        // try to bind/authenticate against ldap
        $ldapbind = @ldap_bind($ldapconn, $ldap_user, $ldap_pass) || forbidden();
        // "LDAP bind successful...";
        error_log("success: " . $_SERVER['REMOTE_ADDR'] . ', user: ' . $_SERVER['PHP_AUTH_USER']);
    }
    ldap_close($ldapconn);
}


// no cache
session_cache_limiter('nocache');
session_start( );
header('Cache-Control: no-cache, no-store, must-revalidate, post-check=0, pre-check=0');
header('Pragma: no-cache');
header("Expires: 0");

if (@$_SESSION['AUTH'] != 1) {
    authenticate();
}

if (empty($_SERVER['PHP_AUTH_USER'])) {
    authenticate();
}

// check credentials
ldap_auth();

// Get requested file name
$path = $_SERVER["REQUEST_URI"];

error_log("serving: " . $_SERVER['REMOTE_ADDR'] . ', user: ' . $_SERVER['PHP_AUTH_USER'] . ', path: ' . $path);

header("Content-Type: ", true);
header("X-Accel-Redirect: /protected" . $path);

?>
