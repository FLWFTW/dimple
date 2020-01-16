The Damn Simple Blog
--------------------

##### Index
* [Introduction](#intro)
* [Usage](#usage)
* [Contributing](#github)

##### <a name='intro'></a> Introduction
There are plenty of content management systems out there, that's without a doubt. This project is born out of my furstration with setting up CMS and blogging platforms that require complex templates, esoteric markup languages, databases, etc. I wanted something that I could plug into my already designed website without disturbing my previous work.

Dimple is a small, lightweight, and (hopefully) secure content management system that allows me to display what I want, where I want it. It is designed to give the user maximum freedom in choosing their layout, file organization, and software ecosystem. By itself Dimple requires no dependencies to run; it is truly plug-and-play. This modularity allows users to select their own markdown parser, their own code highlighter, etc. Dimple does one thing: displays content chronologically.

##### <a name='usage'></a>Usage
Dimple gets its name because it's designed to be Damn Simple. The entire CMS lives in a single file and loads and runs its content with two lines of code.


```php
<?php
   require_once( './dimple/dimple.php' );
   $d = new dimple('./path/to/content/'); //Path is optional and defaults to './content/'
   $d->run();
?>
```

Your content is displayed wherever you place *run()*.

Being a flat-file CMS, each file (Not including hidden files) in your content directory is treated as an individual blog entry. Content files require a bit of meta-data in order for Dimple to understand what to display. For instance, this entry's file starts out like this:


```markdown
@meta-start
@author Will Sayin
@publish-date 2020-01-09
@title Introducing the Dimple Blogging Platform
@access-name dimple-intro
@tags dimple, php, blog, cms
@encoding markdown
@hidden false
@end meta

##### The Damn Simple Blog

##### Index
* [Introduction](#intro)
...etc
```

- **@meta-start** (*required*) - Must be the first line in the file. Starts the meta-data block.
- **@author** (*required*) - Who wrote the post?
- **@publish-date** (*required*) - When was the post published?
- **@title** (*required*) - What is the title to display?
- **@access-name** (*required*) - What is the url to the post?
- **@tags** (*required*) - What categories does this post fall under?
- **@encoding** (*optional*) - How is the post encoded? Current options are markdown (default) or plaintext.
- **@hidden** (*optional*) - Should the post be hidden from the index listing? (Defaults to false)
- **@end meta** (*required*) - Ends the meta-data block

***Note:*** Dimple doesn't do any parsing or processing of content. I leave it to the user to choose their own library to use. I recommend using [marked.js](https://marked.js.org) to parse your markdown and [highlight.js](https://highlightjs.org/) to highlight any code.

##### <a name='github'></a>Contributing
Check out the github page at https://www.github.com/flwftw/dimple

Thanks for checking it out!
