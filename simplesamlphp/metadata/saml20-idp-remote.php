<?php

$metadata['https://idp.nebraskacloud.org/simplesaml/saml2/idp/metadata.php'] = [
  'metadata-set'        => 'saml20-idp-remote',
  'entityid'            => 'https://idp.nebraskacloud.org/simplesaml/saml2/idp/metadata.php',
  'SingleSignOnService' => [
    0 => [
      'Binding'  => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://idp.nebraskacloud.org/simplesaml/saml2/idp/SSOService.php',
    ],
  ],
  'SingleLogoutService' => [
    0 => [
      'Binding'  => 'urn:oasis:names:tc:SAML:2.0:bindings:HTTP-Redirect',
      'Location' => 'https://idp.nebraskacloud.org/simplesaml/saml2/idp/SingleLogoutService.php',
    ],
  ],
  'certData'     => 'MIID5zCCAs+gAwIBAgIJALAzeG8DvdODMA0GCSqGSIb3DQEBCwUAMIGJMQswCQYDVQQGEwJVUzERMA8GA1UECAwITmVicmFza2ExDjAMBgNVBAcMBU9tYWhhMQ4wDAYDVQQKDAVFU1VDQzEjMCEGA1UEAwwacHJvZC1pZHAubmVicmFza2FjbG91ZC5vcmcxIjAgBgkqhkiG9w0BCQEWE3Npc2FhY3NvbkBlc3VjYy5vcmcwHhcNMTUxMjE4MTY1NzE2WhcNMjUxMjE3MTY1NzE2WjCBiTELMAkGA1UEBhMCVVMxETAPBgNVBAgMCE5lYnJhc2thMQ4wDAYDVQQHDAVPbWFoYTEOMAwGA1UECgwFRVNVQ0MxIzAhBgNVBAMMGnByb2QtaWRwLm5lYnJhc2thY2xvdWQub3JnMSIwIAYJKoZIhvcNAQkBFhNzaXNhYWNzb25AZXN1Y2Mub3JnMIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAwA2JWUTOf4Arp4zvmlRZernvQ+JdpSYzWtyi3m2aD3D1t0UQZAg1yTuJKvHyJKmtp4cUMvbKus7WZXnZGMKIzmXLGU/iZ/u1Wewmgca7NgUjLWQ0+r4yu3JWcKEg1UyRz89yNaCEGOvI9Sq6aQ1fQ/L9NZ4CQrXsJ6AVNpn868O2wuc1GRET3qOlECJyFTIajGJjRbq8bVaIzCmeA9U6ojbUFa6WETWYRwZLH29CoRuy1zh6OknPTEKpwDSyr588vBi52jPUH/Sqm6KTuosavvBwVbWFsI/gIbLrllWjiznj/syNQ2W+CH1T29QJdjbLmKZfXAuueOXnk0GF8gEEdQIDAQABo1AwTjAdBgNVHQ4EFgQUqRNToGX3Mhn/guIrwnCadyTwXVIwHwYDVR0jBBgwFoAUqRNToGX3Mhn/guIrwnCadyTwXVIwDAYDVR0TBAUwAwEB/zANBgkqhkiG9w0BAQsFAAOCAQEACbRUJhxfXxZctCDVm4KrrjNICn6cJjAqnIvjrpkfh9Qf5Y/2kg5kM3j8xBhBc9P+EgL0IkCPc7aMy9IAgrQ0Yb9MlTR+ejtRz+i+6wjxL72KTSDPV1BLeFelne6A/AoKiN/50A6u19y6BJNdfuzaVB2U7GDSyl5D7DEMCUtpY3dutKapdI3Lm40QT7R2D5kFTr4N5CXGSTHmWCgnBZVF9clxrkNXDr1Ovm7yCuNfYQxRecXRambWeNNAKAA7+PyWia6XQpUj6JDTdneidaYgLmnQUR8yqrFRUl6XccCxJGM4bMAeGaBQ42E/qWn75EC3M0rUzN/TRBeJl3Lo/2avgg==',
  'NameIDFormat' => 'urn:oasis:names:tc:SAML:2.0:nameid-format:transient',
];
