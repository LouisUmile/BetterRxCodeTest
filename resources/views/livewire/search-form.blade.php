<div>
    <br />
    <form wire:submit="npiPoll">
        <label for='first_name'>First Name</label>
        <input type='text' id='first_name' name='first_name' wire:model='FirstName' />
        <br /><br />
        <label for='last_name'>Last Name</label>
        <input type='text' id='last_name' name='last_name' wire:model='LastName'/>
        <br /><br />
        <label for='npi_number'>NPI Number</label>
        <input type='text' id='npi_number' name='npi_number' wire:model='NpiNumber'/>
        <br /><br />
        <label for='taxonomy_description'>Taxonomy Description</label>
        <input type='text' id='taxonomy_description' name='taxonomy_description' wire:model='TaxonomyDesc'/>
        <br /><br />
        <label for='city'>City</label>
        <input type='text' id='city' name='city' wire:model='City'/>
        <br /><br />
        <label for='state'>State</label>
        <input type='text' id='state' name='state' wire:model='State'/>
        <br /><br />
        <label for='zipcode'>Zip Code</label>
        <input type='text' id='zipcode' name='zipcode' wire:model='ZipCode'/>
        <br /><br />
        <input type=submit class='formSubmit'></imput>
    </form>

    <div>
        @if(!empty($providers))
        <br />
           <label>Page {{ $pageOffset + 1 }}: {{ $resultCount }} results found</label>
           <br /><br />
            @if($pageOffset > 0)
                <form wire:submit="prevPage"><button type="submit">PREV</button></form> 
            @endif
           <br />
            @foreach ($providers as $p)
                <?php
                    if(array_key_exists('authorized_official_first_name', $p['basic']))
                    {
                        // Depending on the nature of the business, the keys for provider first and last name may be appended by
                        //   "authorized_official_"
                        $linkName = $p['basic']['authorized_official_last_name'] . ", " . $p['basic']['authorized_official_first_name'] 
                                    . ": " . $p['addresses'][0]['address_1'];
                    }
                    else 
                    {
                        $linkName = $p['basic']['last_name'] . ", " . $p['basic']['first_name'] . ": " . $p['addresses'][0]['address_1'];
                    }
                ?>
                <a wire:click="$dispatch('openModal', { component: 'npi-modal', arguments: { npiNumber: {{ $p['number'] }} }})">{{ $linkName }}</a><br />
            @endforeach
            @if($moreResults)
                <br /><form wire:submit="nextPage"><button type="submit">NEXT</button></form> 
            @endif    
        @endif    
    </div>
</div>


