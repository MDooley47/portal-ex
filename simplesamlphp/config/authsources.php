<?php

$config = array(

    'admin' => array(
        'core:AdminPassword',
    ),

    'default-sp' => array(
        'saml:SP',
        'privatekey' => 'saml.pem',
        'certificate' => 'saml.crt',
        'idp' => 'https://idp.nebraskacloud.org/simplesaml/saml2/idp/metadata.php',
    ),
);
