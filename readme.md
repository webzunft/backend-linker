Backend Linker Plugin

## Description

The goal of the plugin is to lead users back to their WordPress backend, when they clicked on a link that leads to an external website for some documentation.

E.g.,

The user has the Image Source Control plugin installed.
Within the pages of the plugin in their WordPress backend, they find links to the documentation of certain features.
When they click on the link, they are led to the documentation on the plugin website, e.g., https://imagesourcecontrol.com/documentation.
Within that documentation, they find paths to certain pages and options in their WordPress backend, e.g., "Go to Settings > Image Sources".

What the Backend Linker plugin now does is wrapping the path "Settings > Image Sources" with a link that points to `https://example.com/wp-admin/options-general.php?page=isc-settings`.
Assuming that the user came from `https://example.com` and the referral link contains certain information, like a UTM parameter.

Visitors to the documentation without such a parameter won’t see a link at all.

To minimize confusion, the links include a small icon linking to an explanation of why they are seeing it which is attached to the footer of the page.

## Usage

Go to _Settings > Reading_ to set the plugin options.

Set _Backend Linker URL String Match_ option to match the string that needs to be in the referrer URL for those users for whom the URLs should be replaced.

Set Strings that need linking under _Backend Linker Match & Targets_.
Each line is a new entry. Separate the string that should be linked from the partial URL that needs to be added using a Pipe symbol.

If you have a string that is also part of another string, then the plugin would replace both, causing wrong markup.
E.g., you have a line for "Plugin > Settings > Options" and "Settings > Options".
Note that the ">" and other special characters could be sanitized to look like "&gt;". Adding two lines, one with an escaped, and another with an unescaped version, helps.

Add a URL into the _Backend Linker URL to more details_ to link to more details in the footer notes. If you leave this empty, no link will show up. 

## Additional notes

You can add the `wbl-source` parameter with a custom URL to simulate, e.g., `wbl-source=http://example.com`.

The plugin does not store the referrer at all – no cookies, no database entry.