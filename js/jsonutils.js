/**************************************************************************************************
* Description: 	Retrieves a JSON string returned by a web service located at a specified URL.
				Utilizes jQuery to make an Ajax call to a web service or a script that
				returns a JSON string
* Arguments: 	targetUrl (String) - a url of a web service or a script that generates JSON.
					Note that targetUrl must include all URL parameters.
**************************************************************************************************/
function getJSON(targetUrl){
    jQuery.ajax({
        type: "GET",
        url: targetUrl,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(data, status, jqXHR) {
            console.log(data);
            /* This function is custom-created for each page that utilizes
            	data from returned JSON.  We need to come up with a better way
            	of generalizing this 
            */
        },
        error: function(jqXHR, status) {
            console.log(status);
        }
    });
}




