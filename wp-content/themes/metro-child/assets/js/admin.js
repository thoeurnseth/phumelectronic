$(document).ready(function(){
		
    /* added this condition, since acf.o was null at certain points for some reason when in the admin backend */
    if (acf.o == null) {
        return;
    }
    
    // update post_id
    acf.screen.post_id = acf.o.post_id;
    acf.screen.nonce = acf.o.nonce;
});