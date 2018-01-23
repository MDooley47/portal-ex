<?php
/**
 * Local Configuration Override
 *
 * This configuration override file is for overriding environment-specific and
 * security-sensitive configuration information.
 *
 * @NOTE: This file is ignored from Git by default with the .gitignore included
 * in ZendSkeletonApplication. This is a good practice, as it prevents sensitive
 * credentials from accidentally being committed into version control.
 * @NOTE Since dotenv is used, this file will not contain any
 * sensitive information; therefore, it is not ingored by Git.
 */

return [
    'db' => [
        'username' => getenv('db_username'),
        'password' => getenv('db_password')
    ]
];
