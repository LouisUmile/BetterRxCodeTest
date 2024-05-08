<div>
    <button wire:click="$dispatch('closeModal')">Close</button>
    <p> this is the modal for {{ $npiNumber }}</p>
    <?php
    /***********************************************
     * 
     * iFrame fails due to remote host blocking request: X-FRAME-OPTIONS set to SAMEORIGIN
     * Grabbing the page via HTTP and parsing out the body fails because all that it returns is 
     *     is javascript used to populate the page and running it locally fails the cross-origin policy
     * TODO: send the JSON NPI # from the original request in the link and create a model class similar to 
     *        the User class which ships with Laravel
     */
    ?>
</div>