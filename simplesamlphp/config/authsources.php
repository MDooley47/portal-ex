<?php

$config = [

    'admin' => [
        'core:AdminPassword',
    ],

    'default-sp' => [
        'saml:SP',
        'privatekey'  => 'saml.pem',
        'certificate' => 'saml.crt',
        'idp'         => 'https://idp.nebraskacloud.org/simplesaml/saml2/idp/metadata.php',
    ],
];
