<div>
    <button wire:click="$dispatch('closeModal')">Close</button>
    <p> this is the modal for {{ $npiNumber }}</p>
    <?php
    /***********************************************
     * 
     * iFrame fails due to remote host blocking request: X-FRAME-OPTIONS set to SAMEORIGIN
     * 
     */
    ?>
</div>