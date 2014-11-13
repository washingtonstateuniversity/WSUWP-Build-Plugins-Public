# WSUWP Search Plugin

Provides a connection to [WSU Search](https://github.com/washingtonstateuniversity/wsu-search/) from WordPress sites at WSU.

Currently adds posts to WSU's `wsu-web` index as the global type of `page`.

If `WSU_LOCAL_CONFIG` is defined as part of your development environment, th index will change to `wsu-local-dev`.

## Document Structure

The current document structure for pages saved by the WSU Search plugin is:

```
{
	"body" : {
		"title":               "Title of the page or post",
		"date":                "2014-07-18 21:38:27",
		"author":              "Jeremy Felt",
		"content":             "Content of the page or post",
		"url":                 "http:\/\/wp.wsu.edu\/2014\/07\/18\/another-test-post\/",
		"generator":           "wsuwp",
		"site_id":             52,
		"hostname":            "news.wsu.dev",
		"site_url":            "news.wsu.dev",
		"network_id":          8,
		"site_category":       [ "uncategorized" ],
		"university_tag":      ["tag"],
		"university_category": [ "academic-subjects" ],
		"university_location": [ "wsu-extension" ]
	}
}
```