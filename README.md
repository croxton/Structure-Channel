#Structure Channel

* Author: [Mark Croxton](http://hallmark-design.co.uk/)

## Version 1.0.0

* Requires: ExpressionEngine 2 and the [Structure](http://buildwithstructure.com/) module

## Description

Structure Channel is a replacement for the Channel module for use with Structure.
Specifically, it can be used to output pagination links instead of Structure's {exp:structure:paginate} tag.

## Why on earth would you want to do this? 

Firstly, because Structure's solution - {exp:structure:paginate} - is rather inefficient. It works by parsing the wrapped {exp:channel:entries} tag and reconstructing the SQL query it runs in order to get the total count and thus generate pagination links. This adds unnecessary overhead to the template.

Secondly, the use of nested tags disables the use of {if no_results} {/if} inside your {exp:channel:entries} tag.

Structure Channel takes a more direct approach by subclassing the Channel module itself and overloading the create_pagination method to generate pagination that works with Structure URIs. You can now use pagination in exactly the same way as the Channel module, and {if no_results} {/if} will work as expected.


## Installation

1. Copy the folder structure_channel to ./system/expressionengine/third_party/


## Parameters

Exactly the same as the Channel module, with one additional (optional) parameter 'pagination_param'. This determines the parameter name used in the pagination query string. By default this is 'p'.


## Sample usage

	{exp:structure_channel:entries channel="my_channel" disable="member_data|categories" dynamic="no" limit="10" paginate="bottom" paginate_base="/my/structure/page/?" pagination_param="page"}

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
