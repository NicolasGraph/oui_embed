h1. oui_embed

Easily embed stuff…

h2. Table of contents

* "Plugin requirements":#requirements
* "Installation":#installation
* "Tag":#tag
* "Example":#example
* "Author":#author
* "Licence":#licence

h2(#requirements). Plugin requirements

oui_instagram’s minimum requirements:

* Textpattern 4.5+
* "Embed":https://github.com/oscarotero/Embed

h2(#installation). Installation

# Download "Embed":https://github.com/oscarotero/Embed by Oscar Otero and paste the embed folder in your textpattern/vendors folder;
# paste the content of the plugin file under the *Admin > Plugins*, upload it and install.

h2(#tag). Tag

h3. oui_embed

Single tag use to embed your stuff.

bc. <txp:oui_embed />

h4. Attributes 

h5. Required

* @url="…"@ - _Default: unset_ - The url from which you want to get any information.

h5. Optional

* @class="…"@ – _Default: oui_instagram_images_ - The css class to apply to the HTML tag assigned to @wraptag@.
* @info="…"@ – _Default: code_ - The information to retrieve from the url feed. Valid values are _title, description, url, type, tags, images, image, imageWidth, imageHeight, code, width, height, aspectRatio, authorName, authorUrl, providerName, providerUrl, providerIcons, providerIcon, publishedDate_ ("More informations":https://github.com/oscarotero/Embed).
* @label="…"@ – _Default: unset_ - The label used to entitled the generated content.
* @labeltag="…"@ - _Default: unset_ - The HTML tag used around the value assigned to @label@.
* @wraptag="…"@ - _Default: ul_ - The HTML tag to use around the generated content.

h2(#example). Example

bc. <txp:oui_embed url="https://www.youtube.com/watch?v=PP1xn5wHtxE" />

h2(#author). Author

"Nicolas Morand":https://github.com/NicolasGraph.

h2(#licence). Licence

This plugin is distributed under "MIT license":https://opensource.org/licenses/MIT.