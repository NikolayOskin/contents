# "Table of contents" generator Laravel package

This package provides clickable Table Of Contents for your posts, news, articles etc based on h2-h6 html tags from input text.

## Usage

Let's say you have some posts (from WYSIWYG editor or something) stored in your database which looks like this:

    <h2>First h2 header</h2>
    <p>Some text</p>    
    <h3>First h3 header</h3>
    <p>Some text</p>    
    <h3>2nd h3 header</h3>
    <p>Some text</p>    
    <h4>First h4 header</h4>
    <p>Some text</p>
    <h4>2nd h4</h4>
    <p>Some text</p>
    <h2>Latest header</h2>

And you have some PostController with show() method which sends your Post model object data to your view.
```php
public function show ($id)
{
    $post = Post::find($id);
    $header = $post->title;
    $body = $post->body;
    return view('post', compact('header', 'body');
}
```

Your 'post.blade.php' view looks stupid simple like this:
```html
<div>
    <h1>{{$header}}</h1>
    {!! $body !!}
</div>
```

This is how you can use this package to generate "Table of contents" in controller:

```php
public function show ($id, Contents $contents)
{
    $post = Post::find($id);
    $header = $post->title;
    $body = $contents->fromText($post->body)->getHandledText();
    $contents = $contents->getContents();
    return view('post', compact('header', 'body', 'contents');
}
```

Now you passed "contents" array where your header tags are stored. Also, as you can see you passed "handledText", which has id attributes inside header html tags.

Your view:

```html
<div>
    <h1>{{$header}}</h1>
    @if(!empty($contents))
        @include('nikolay-oskin.contents.table', $contents)
    @endif
    {!! $post !!}
</div>
```

table.blade.php is the view with ul-li tags where you can add your css styles or make any other customization.

#### Result
![Alt text](http://joxi.ru/gmvBYaNHqZ0pdm.png "Optional title")

### Options
By default contents created for h2-h6 tags. You can set headers tags which have been included in 'Table of contents'.

Example:
```php
    $post = Post::find($id);
    $contents->fromText($post->body)->setTags(['h2', 'h3']);
    $handledText = $contents->getHandledText();
    $contents = $contents->getContents(); 
    // Only h2 and h3 headers in table of contents
```

You can also set text min-length number so if the text length is below this number then $contents array will be empty and $contents->getHandledText() returns original text.

Example:
```php
    $post = Post::find($id);
    $contents->fromText($post->body)->setTags(['h2', 'h3'])->setMinLength(5000);
    $handledText = $contents->getHandledText();
    $contents = $contents->getContents();
    // Table of contents will not be shown for texts length less than 5000 chars.
```

### Install

For Laravel 5.5+

```sh
composer require nikolay-oskin/contents
```

To copy views to your resource/views directory:

```sh
php artisan vendor:publish --provider="NikolayOskin\Contents\Contents"
```