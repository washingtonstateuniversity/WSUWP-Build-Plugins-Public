# Table of Contents Generator

Provides a shortcode for WordPress to generate a table of contents using the [jQuery Table of Contents plugin](http://projects.jga.me/toc/#toc4).

When `[wsuwp_toc]` is used in your content, a table of contents will be automatically generated in a `<div id="toc"></div>` element.

If `[wsuwp_toc position="bottom"]` is used, this `div` element will be generated at the end of the HTML document so that it can be manually positioned. By default, or if `[wsuwp_toc position="content"]` is used, this `div` element will be output where the shortcode is used.

The headers that are used to create the table of contents can be explicitly defined with `[wsuwp_toc headers="h1,h2,h3"]`. By default, `h1,h2,h3,h4` are used.
