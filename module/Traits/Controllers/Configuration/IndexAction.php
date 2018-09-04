<?php

namespace Traits\Controllers\Configuration;

use Zend\View\Model\ViewModel;

trait IndexAction
{
    /**
     * Displays the index page for Configuration.
     *
     * @return ViewModel
     */
    public function indexAction()
    {
        $TABLES = [
            'apps' => [
                [
                    'slug'     => 'orG332',
                    'name'     => 'Google Drive',
                    'url'      => 'https://accounts.google.com/ServiceLogin?service=wise&ltmpl=drive',
                    'iconPath' => '/images/google_drive.png',
                ],
                [
                    'slug'     => '29ry38',
                    'name'     => 'Google Docs',
                    'url'      => 'https://docs.google.com',
                    'iconPath' => '/images/google_docs.png',
                ],
                [
                    'slug'     => 'ri12io',
                    'name'     => 'Digital Ocean',
                    'url'      => 'https://digitalocean.com',
                    'iconPath' => '/images/digital_ocean.png',
                ],
                [
                    'slug'     => 'JH3ed1',
                    'name'     => 'Gmail',
                    'url'      => 'https://gmail.com',
                    'iconPath' => '/images/gmail.png',
                ],
                [
                    'slug'     => 'sNe34a',
                    'name'     => 'UNOmaha',
                    'url'      => 'https://unomaha.edu',
                    'iconPath' => '/images/unomaha.jpg',
                ],
                [
                    'slug'     => 'via3s3',
                    'name'     => 'Ubuntu',
                    'url'      => 'https://ubuntu.com',
                    'iconPath' => '/images/ubuntu.png',
                ],
            ],
            'attributes'     => [],
            'groups'         => [],
            'groupApps'      => [],
            'groupTypes'     => [],
            'ipAddresses'    => [],
            'ownerTabs'      => [],
            'ownerTypes'     => [],
            'privileges'     => [],
            'settings'       => [],
            'tabApps'        => [],
            'tabs'           => [],
            'userGroups'     => [],
            'userPrivileges' => [],
            'users'          => [],
        ];

        return new ViewModel([
            'seed_tables' => $TABLES,
        ]);
    }
}
