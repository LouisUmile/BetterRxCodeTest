<?php
// TODO - refactor with name NpiController instead of npiController
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;

use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class npiController extends Controller
{
    public $npi_number;
    public $first_name;
    public $last_name;
    public $taxonomy_description;
    public $city;
    public $state;
    public $zipcode;

    //
    public function getLookup($formJson) 
    {


        // Log requests for debugging
        // TODO - improve msg: add form fields for debugging & add formatting
        \Log::info('Performing inquiry on form data: ' . $formJson);

        // unpack json-encoded form data
        $formData = json_decode($formJson);
        
        // Create a new Guzzle client instance
        $client = new Client();

        // basic endpoint URL w/version
        $apiUrl = "https://npiregistry.cms.hhs.gov/api/?version=2.1&limit=1200";
        
        $apiUrl .= (strlen($formData->NpiNumber) == 10 ? "&number=" . urlencode($formData->NpiNumber) : "");

        // for the purpose of this exercise, I guessed that we want first name aliases. 
        // In a live situation, I would ask the project manager for clarification
        $apiUrl .= (strlen($formData->FirstName) > 0 ? "&use_first_name_alias=true&first_name=" . urlencode($formData->FirstName) : "");

        $apiUrl .= (strlen($formData->LastName) > 0 ? "&last_name=" . urlencode($formData->LastName) : "");

        $apiUrl .= (strlen($formData->TaxonomyDesc) > 0 ? "&taxonomy_description=" . urlencode($formData->TaxonomyDesc) : "");

        $apiUrl .= (strlen($formData->City) > 0 ? "&city=" . urlencode($formData->City) : "");

        $apiUrl .= (strlen($formData->State) > 0 ? "&state=" . urlencode($formData->State) : "");

        $apiUrl .= (strlen($formData->ZipCode) > 0 ? "&postal_code=" . urlencode($formData->ZipCode) : "");


        \Log::info("Query URL: " . $apiUrl);

        try {
            $response = $client->get($apiUrl);
            
            $data     = json_decode($response->getBody(), true);

//            \Log::info($data);
            if(array_key_exists('Errors', $data)) {
                \Log::error("Error returned: " . $data['Errors'][0]['description']);
                return view('npi_error', ['errMsg' => $data['Errors'][0]['description']]);
            } else if (count($data['results']) == 0) {
                \Log::info("No results returned");
                return view('npi_error', ['errMsg' => 'No results found']);
            } else {                
                // how many results did we get?
                \Log::info("Results returned: " . $data['result_count']);
                //return view('npi_search_results', ['npiData' => $data]);
        
           /*     
                $page = !empty(Request::input('page')) ? Request::input('page') : '1';
                $perPage = "1";
                $offset = ($page - 1) * $perPage;
                //$current_page = LengthAwarePaginator::resolveCurrentPage();
                $current_page = $page;
*/
                // create collection
                $providersData = collect($data['results']);
            }
            
          //return redirect('/'); // TODO: remove after testing
        } catch (\Exception $ex) {
            \Log::error($ex->getMessage());
            
            // If this were production, I would aim for having a small mapping of default  
            // msgs so as to improve user readability and improve security
            return view('npi_error', ['errMsg' => "Ecception: " . $ex->getMessage()]);
        }
    }
}
