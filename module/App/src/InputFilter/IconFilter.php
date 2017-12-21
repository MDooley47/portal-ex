<?php

namespace App\InputFilter;

use Zend\InputFilter\FileInput;
use Zend\InputFilter\InputFilter;
use Zend\Validator\File\IsImage;

class IconFilter extends InputFilter
{
    /**
     * Constructs IconFilter
     *
     * @return IconFilter $this
     */
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

        return $this;
    }
}

?>
