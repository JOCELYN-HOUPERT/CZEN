<?php
$opensslConf = 'C:\\Users\\cotyn\\.config\\herd\\bin\\php84\\extras\\ssl\\openssl.cnf';

if (file_exists($opensslConf)) {
    putenv('OPENSSL_CONF=' . $opensslConf);
}

$config = [
    'digest_alg' => 'sha512',
    'private_key_bits' => 4096,
    'private_key_type' => OPENSSL_KEYTYPE_RSA,
];

$res = openssl_pkey_new($config);

if (!$res) {
    echo 'Erreur OpenSSL : ' . openssl_error_string();
    exit;
}

openssl_pkey_export($res, $privateKey);
$publicKey = openssl_pkey_get_details($res)['key'];

if (!is_dir('config/jwt')) mkdir('config/jwt');
file_put_contents('config/jwt/private.pem', $privateKey);
file_put_contents('config/jwt/public.pem', $publicKey);
echo 'Clés générées avec succès!';
