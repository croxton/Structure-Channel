#Structure Channel

* Author: [Mark Croxton](http://hallmark-design.co.uk/)

## Version 1.0.1

* Requires: ExpressionEngine 2.4+

* Optional: [Structure](http://buildwithstructure.com/) module

## Description

Structure Channel is a drop-in replacement for the Channel module which generates query string pagination (?p=1) rather than using URI segments /P1/

## Why on earth would you want to do this? 

If you are using [Structure](http://buildwithstructure.com/) or the Pages module with [Freebie](http://github.com/averyvery/Freebie) or .htaccess mod_rewrite rules to create custom URLs, then you will quickly find that the native pagination doesn't work.

## Installation

1. Copy the folder structure_channel to ./system/expressionengine/third_party/
2. In the Control Panel, go to Add-Ons > Modules and click the Install link for Structure Channel


## Parameters

Exactly the same as the Channel module, with one additional (optional) parameter:

### pagination_param = ""

This determines the parameter name used in the pagination query string. By default this is 'p'.


## Sample usage

	{exp:structure_channel:entries 
		channel="my_channel" 
		disable="member_data|categories" 
		dynamic="no" 
		limit="10" 
		paginate="bottom" 
		paginate_base="/my/custom/uri/?" 
		pagination_param="page"
	}

		{title} <br />

		{paginate} 
			<p>Page {current_page} of {total_pages}</p>
		
			<p>{pagination_links}</p>
		
			{if previous_page}
			<a href="{auto_path}">Previous Page</a> &nbsp;
			{/if}

			{if next_page}
			<a href="{auto_path}">Next Page</a>
			{/if}	
		{/paginate}	

		{if no_results}
			Sorry, no entries were found.
		{/if}
	{/exp:structure_channel:entries}
