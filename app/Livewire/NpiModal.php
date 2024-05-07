<?php

namespace App\Livewire;

//use Livewire\Component;
use LivewireUI\Modal\ModalComponent;
use GuzzleHttp\Client;

class NpiModal extends ModalComponent
{
    public $npiNumber;
    public $payload;

    public function render()
    {
        // Create a new Guzzle client instance
        $client = new Client();

        $response = $client->get("https://npiregistry.cms.hhs.gov/provider-view/" . $this->npiNumber);
        //$this->payload = $response->getBody();
        \Log::info("Modal response: " . $response->getBody());

        return view('livewire.npi-modal');
    }
}
