# WSU Maps Shortcode

This plugin provides a shortcode to embed maps from http://maps.wsu.edu in your content.

The only required attribute is the map's ID. When you click "Link/Embed" while on maps.wsu.edu, you will receive HTML that looks like this:

```
<iframe width="214" height="161" frameborder="0"
        scrolling="no" marginheight="0" marginwidth="0"
        src="http://map.wsu.edu/t/68DE9EF" ></iframe>
```

The piece to copy as the map ID in the above HTML is `68DE9EF`.

This can then be used in WordPress as the following shortcode: `[wsuwp_map id="68DE9EF"]`

By default, this will embed a medium size (354x266) map in your page.

You can change the size with another attribute: `[wsuwp_map id="68DE9EF" size="large"]`

This will embed a large (495x372) map in your page.

The available sizes are as follows:

* small - 214x161
* medium - 354x266
* large - 495x372
* largest - 731x549

A custom size can be used by defining the width and height manually: `[wsuwp_map id="68DE9EF" width=400 height=200]`

In some cases, you may have a custom map created by University Communications. Use your alias as part of the shortcode rather than the ID:

`[wsuwp_map alias="my-custom-code" size="largest"]`