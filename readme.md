# "Table Of Contents" generator Laravel package

This package provides clickable Table Of Contents for your posts, news, articles etc based on h2-h6 html tags from input text.

## How to use

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

And you have some PostController with show() method which sends your Post model object to your view.
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

This is how you can use this package to generate Table-Of-Contents in controller:

```php
public function show ($id, Contents $contents)
{
    $post = Post::find($id);
    $header = $post->title;
    $contents->fromText($post->body);
    $body = $contents->getHandledText();
    $contents = $contents->getContents();
    return view('post', compact('header', 'body', 'contents');
}
```

Now you passed "contents" array where your header tags are stored. Also, as you can see you passed "handledText", which has id attributes inside header html tags.

Your view:

```html
<div>
    <h1>{{$header}}</h1>
    @include('nikolay-oskin.contents.table', $contents)
    {!! $post !!}
</div>
```

table.blade.php is the view with ul-li tags where you can add your css styles or make any other customization.

### Result
![Alt text](http://joxi.ru/gmvBYaNHqZ0pdm.png "Optional title")