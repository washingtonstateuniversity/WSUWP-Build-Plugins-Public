# Public WSU Build Plugins for the WSUWP Platform

The plugins in this repository are mirrored from their official locations so that we can easily deploy them as part of our version controlled environment.

Most plugins are updated directly from their ZIP file on WordPress.org. Several plugins are forks or have hotfixes that should be maintained until the upstream project matches. Check the lists below before updating any of the plugins.

## Forks

There a couple plugins that we have slight forks of in GitHub rather than direct hotfixes. In general, we should avoid this unless we are actively contributing upstream via that fork or the plugin has been abandoned upstream.

* [Duplicate and Merge Posts](https://github.com/Exygy/duplicate-and-merge-posts) ([Forked](https://github.com/washingtonstateuniversity/duplicate-and-merge-posts))
* [WP Document Revisions](https://github.com/benbalter/wp-document-revisions/) ([Forked](https://github.com/washingtonstateuniversity/wp-document-revisions/))
* [MSM Sitemap](https://github.com/Automattic/msm-sitemap) ([Forked](https://github.com/washingtonstateuniversity/msm-sitemap))

## Hotfixes

If a bug has been identified in a plugin, but has not yet been fixed upstream, we may include a hotfix in this repository to avoid PHP errors in production. In most cases, we should also submit a pull request to address the issue upstream.

* Gutenberg - [hotfix](https://github.com/washingtonstateuniversity/WSUWP-Build-Plugins-Public/commit/fc9a52e08ffdd452164f5ee934fa9776961354bc), [issue](https://github.com/WordPress/gutenberg/issues/7133)
* Rewrite Rules Inspector - [hotfix](https://github.com/washingtonstateuniversity/WSUWP-Build-Plugins-Public/commit/4bf546545492e4bf3ce9ede38219571bc3c06290), [pull request](https://github.com/Automattic/Rewrite-Rules-Inspector/pull/24)
* S3 Uploads - [hotfix](https://github.com/washingtonstateuniversity/WSUWP-Build-Plugins-Public/commit/206de38c45d7319ac197be527e43262f44a20f23), [pull request](https://github.com/humanmade/S3-Uploads/pull/215)
* BU Navigation - [hotfix](https://github.com/washingtonstateuniversity/WSUWP-Build-Plugins-Public/commit/0a71e5d7daa23d04257fb8e021c7f7706d568d16), [pull request](https://github.com/bu-ist/bu-navigation/pull/34)
* Edit Flow - [hotfix](https://github.com/washingtonstateuniversity/WSUWP-Build-Plugins-Public/commit/4ccb8de19fa9496b24bacf6bfd2aee83ca792229#diff-592a75abc19b2e6f57eff4284260bb11), [pull request](https://github.com/Automattic/Edit-Flow/pull/357)
* Editorial Access Manager - [hotfix](https://github.com/washingtonstateuniversity/WSUWP-Build-Plugins-Public/commit/37482e12d300cb8167697e04f54d6c055eafc5f8), [pull request](https://github.com/tlovett1/editorial-access-manager/pull/24)
* Image Shortcake - [hotfix](https://github.com/washingtonstateuniversity/WSUWP-Build-Plugins-Public/commit/55c54b2527970e3a1806f33265eb341d6434d815), No pull request (!)
* TablePress Datatables Buttons - [hotfix](https://github.com/washingtonstateuniversity/WSUWP-Build-Plugins-Public/commit/671873557f8f825a738fe5bffbe6919e4a58a80b) - WSU decision, no need for pull request.
* TablePress Responsive Tables - [hotfix](https://github.com/washingtonstateuniversity/WSUWP-Build-Plugins-Public/commit/388851e874a1646ccb551d8547e81944174e8aa8) - WSU decision, no need for pull request.
