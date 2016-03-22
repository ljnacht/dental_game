/**************************************************************************************************
* Description: 	Retrieves a JSON string based on an SQL query.
				Utilizes jQuery to rest/resultstojson.php - a PHP script that generates
				a JSON string based on an SQL query
* Arguments: 	sql 		- 	a MySQL SELECT query
				targetDiv 	- 	an HTML div tag where the target table will be dynamically built 
								using jQuery
				tableName	- 	name of the database table (for label display)
**************************************************************************************************/
function runQuery(sql, targetDiv, tableName) {
    jQuery.ajax({
        type: "GET",
        url: "rest/resultstojson.php?sql=" + sql,
        contentType: "application/json; charset=utf-8",
        dataType: "json",
        success: function(data, status, jqXHR) {
			buildResultsTable(data, targetDiv, tableName);
        },
        error: function(jqXHR, status) {
            console.log(status);
        }
    });
}



/**************************************************************************************************
* Description: 	Builds a generic HTML table based on a JSON string.  
* Arguments: 	sql 		- 	data - JSON object
				targetDiv 	- 	an HTML div tag where the target table will be dynamically built 
								using jQuery
				tableName	- 	name of the database table (for label display)
**************************************************************************************************/
function buildResultsTable(data, targetDiv, tableName) {
    var k = 0;
    var $table = $('<table/>');
    
    $.each(data, function(i, item) {
        // Create table header row using column names from database table
        if (k == 1) {
            var $header = $('<tr/>');
            $.each(item, function(j, item1) {
                $header.append('<td class="problemTableHeader">' + j + '&nbsp;</b>');
            });
            $table.append($header);
        }
        k++;
        // Create table body
        var $row = $('<tr/>');
        $.each(item, function(j, item1) {
            $row.append('<td>' + item1 + '&nbsp;</td>');
        });
        $table.append($row);
    });
    if(tableName != ''){
        targetDiv.innerHTML+=('<p class="dbTableName">' + tableName + '</p>');
    }
    targetDiv.innerHTML+=($table);
}


/**************************************************************************************************
* Description: 	Empties target HTML div tag and calls runQuery function
* Arguments: 	None
**************************************************************************************************/
function runUserQuery() {
    var targetDiv = $('#divQueryResults');
    targetDiv.empty();
    runQuery($('#txtSql').val(), targetDiv, '');
}


/**************************************************************************************************
* Description: 	Clears/restarts a query attempt
* Arguments: 	None
**************************************************************************************************/
function clearUserQuery(){
    $('#txtSql').empty();
    $('#divQueryResults').empty();
}


/**************************************************************************************************
* Description: 	Generates HTML tables based on SQL query results.  These tables are 'question' 
				tables - they display data students need to build a query/report.
				Right now the queries are hardcoded - we'll need pull this data from a 
				database in the long run
* Arguments: 	None
**************************************************************************************************/
function getProblemTables() {
    var targetDiv = $('#divProblemTables');
    targetDiv.empty();
    runQuery('SELECT employeeNumber, OfficeCode, lastName, firstName, jobTitle FROM classicmodels.employees limit 5;', targetDiv, 'classicmodels.employees');
    runQuery('SELECT phone, officeCode, territory, state, city  FROM classicmodels.offices limit 5;', targetDiv, 'classicmodels.offices');
    //runQuery('SELECT customerName, phone, contactLastName, customerNumber FROM classicmodels.customers limit 5;', targetDiv, 'classicmodels.customers');
    //runQuery('DESCRIBE classicmodels.employees;', targetDiv);
}


/**************************************************************************************************
* Description: 	This is not finished yet...
**************************************************************************************************/
function getPuzzleTables() {
    var targetDiv = $('#divPuzzleTables');
    targetDiv.empty();
    runQuery('SELECT employeeNumber, OfficeCode, lastName, firstName, jobTitle FROM classicmodels.employees limit 5;', targetDiv, 'classicmodels.employees');
    runQuery('SELECT phone, officeCode, territory, state, city  FROM classicmodels.offices limit 5;', targetDiv, 'classicmodels.offices');
    //runQuery('SELECT customerName, phone, contactLastName, customerNumber FROM classicmodels.customers limit 5;', targetDiv, 'classicmodels.customers');
    //runQuery('DESCRIBE classicmodels.employees;', targetDiv);
}

