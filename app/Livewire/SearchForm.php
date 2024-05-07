<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Rule;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

// TODO - implement LengthAwarePaginator
//use Illuminate\Support\Collection;
//use Illuminate\Pagination\LengthAwarePaginator; 

class SearchForm extends Component
{
    public $perPage         = 51; // We want 50 results displayed per page, and one extra to indicate whether there are more results
    public $pageOffset      = 0;
    public $moreResults     = false;
    public $resultCount     = "";
    public $NpiApiVersion   = "2.1";
    public $post            = '';
    public $FirstName       = '';
    public $LastName        = '';
    public $NpiNumber       = '';
    public $TaxonomyDesc    = '';
    public $City            = '';
    public $State           = '';
    public $ZipCode         = '';
    public $providers;

    public function prevPage() 
    {
//        \Log::info("function prevPage() called");
        $this->pageOffset--;
        \Log::info("function prevPage() called. Page offset changed to " . $this->pageOffset);
        $this->npiPoll();
    }

    public function nextPage() 
    {
//        \Log::info("function nextPage() called");
        $this->pageOffset++;
        \Log::info("function nextPage() called. Page offset changed to " . $this->pageOffset);
        $this->npiPoll();
    }

    public function npiPoll() 
    {
        \Log::info("Enter npiPoll");

        // Create a new Guzzle client instance
        $client = new Client();

        // basic endpoint URL w/version
        //   adding 1 to $this->perPage for the limit ensures we can predict whether there are more records and thus decide 
        //   whether to display a "next" button 
        $apiUrl = "https://npiregistry.cms.hhs.gov/api/?version=" . $this->NpiApiVersion . "&limit=" . ($this->perPage);

        // Page offset
        $apiUrl .= ($this->pageOffset > 0 ? "&skip=" . ($this->pageOffset * 50) : "");


        // NPI number
        $apiUrl .= (strlen($this->NpiNumber) == 10 ? "&number=" . urlencode($this->NpiNumber) : "");

        // for the purpose of this exercise, I guessed that we want first name aliases. 
        // In a live situation, I would ask the project manager for clarification
        $apiUrl .= (strlen($this->FirstName) > 0 ? "&use_first_name_alias=true&first_name=" . urlencode($this->FirstName) : "");

        $apiUrl .= (strlen($this->LastName) > 0 ? "&last_name=" . urlencode($this->LastName) : "");

        $apiUrl .= (strlen($this->TaxonomyDesc) > 0 ? "&taxonomy_description=" . urlencode($this->TaxonomyDesc) : "");

        $apiUrl .= (strlen($this->City) > 0 ? "&city=" . urlencode($this->City) : "");

        $apiUrl .= (strlen($this->State) > 0 ? "&state=" . urlencode($this->State) : "");

        $apiUrl .= (strlen($this->ZipCode) > 0 ? "&postal_code=" . urlencode($this->ZipCode) : "");

        \Log::info("Query URL: " . $apiUrl);

        try {
            $response = $client->get($apiUrl);

            $data = json_decode($response->getBody(), true);

            if(array_key_exists('Errors', $data)) {
                \Log::error("Error returned: " . $data['Errors'][0]['description']);
                return view('npi_error', ['errMsg' => $data['Errors'][0]['description']]);
            } else if (count($data['results']) == 0) {
                \Log::info("No results returned");
                return view('npi_error', ['errMsg' => 'No results found']);
            } else {                
                // how many results did we get?
                $this->resultCount = $data['result_count'];

                \Log::info("Results returned: " . $this->resultCount);
                
                if ($this->resultCount > 50)
                {
                    $this->moreResults = true;

                    // We only want 50 results per page. The extra one grabbed will determine
                    //   whether there are more results & whether to display the "next" button  
                    // Keep the boolean on the end of the array unless we decide we want to
                    //   give different result order than what's returned from the remote API
                    $this->providers = array_slice($data['results'], 0, 50, true);
                    
                    // Adjust result count for display; We return 50 results per page, NOT 51 
                    $this->resultCount--; 
                }
                else 
                {
                    $this->providers = $data['results'];
                }

                $response->getBody()->close();
            }
        } catch (\Exception $ex) {
            \Log::error($ex->getMessage());
    
            // If this were production, I would aim for having a small mapping of default  
            // msgs so as to improve user readability and improve security
            return view('npi_error', ['errMsg' => "Exception: " . $ex->getMessage()]);
        }
//        \Log::info($this->providers);
        return view('livewire.search-form')->with('providers', $this->providers);        
    }

    public function render()
    {
        //return view('livewire.search-form')->with('providers', $this->providers);
        return view('livewire.search-form');
    }
}
