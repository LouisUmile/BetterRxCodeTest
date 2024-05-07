<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class npiSearchForm extends Form
{
    //# TODO: add Rules

    public $FirstName    = '';
    public $LastName     = '';
    public $NpiNumber    = '';
    public $TaxonomyDesc = '';
    public $City         = '';
    public $State        = '';
    public $ZipCode      = '';
}
