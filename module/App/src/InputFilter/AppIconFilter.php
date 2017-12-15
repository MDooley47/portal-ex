<?php

namespace App\InputFilter;

use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\Validator\File\IsImage;

class AppIconFilter extends InputFilter
{

    public function __construct()
    {
        $this->add([
            'name' => 'icon',
            'type' => FileInput::class,
            'required' => true,
            'validators' => [
                [
                    'name'    => 'FileUploadFile',
                ],
                [
                    'name' => 'FileIsImage',
                ],
            ],
            'filters'  => [
                [
                    'name' => 'FileRenameUpload',
                    'options' => [
                        'target'=>'/volumes/storage/images/',
                        'useUploadName'=>false,
                        'useUploadExtension'=>true,
                        'overwrite'=>true,
                        'randomize'=>true,
                    ],
                ],
            ],
        ]);
    }
}

?>
