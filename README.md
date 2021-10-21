# Welcome to your rapid sandbox

**What on earth does that mean?**

A little backstory. After countless times of firing up a new Laravel app, making some models, migrations, controllers,
simple views and other boilerplate just to play around, I started keeping a copy of an extremely simple blog that I
could copy over and use. But before I go further, let me put this out there:

***THIS IS NOT MEANT TO BE USED IN PRODUCTION!!!!***

Like I said above, this is simply a quick way to play with different things. For example, when I see a new Vue UI
framework I want to try out, I can fire a copy of this up and play around with the SPA, using the API this project
exposes.

**But why a blog?**

Why not? Blogs aren’t only simple, but they offer a ton of different scenarios we must account for as developers. You’ve
got file uploads, input sanitization, tagging, authorization, date/time handling and a bunch of other real-world
examples.

**I’m sold! What do I do?**

Installation is quick and easy. Clone, download, fork, whatever this project. If you want to use Docker, it comes with
Sail already configured. By default, this uses a sqlite database (the file will be created for you), but if you wish to
use MySQL, simply uncomment the pertinent parts in docker-compose.yml and update your .env file. After that you can
quickly:


    composer install  
    
    vendor/bin/sail up  

    vendor/bin/sail artisan lblog:go  

The second command will create your .env file, create a blank sqlite database and see the database with 20 random posts
and some users. While most of that is random, there’s a few items that aren’t. First off is 3 users.

admin@example.com

editor@example.com

contributor@example.com

For each of those the password is ‘secret’ (without the ‘). Now do you see why I say, “don’t use this in production?”

**So, what all is included here?**

Well of course we have blog posts. They are very simple. A title, description, body (and body format – more on that
later), image, tags, author and published date/time.

The image system has a very quick and dirty format system. When you upload images, they are resized to a few different
formats for testing out things like different layouts. This can all be configured in config/images.php and you can
create new formatters as you see fit.

So, what about that “body format”. Well first off, THERE IS ZERO REAL FILTERING OF INPUTS. Out of the box there’s two
filters the inputted text can run through before being displayed in a post. First is default, which does nothing more
than run nl2br. The second is raw, which does nothing. If you want something more advanced, like maybe a markdown
format, you can easily create new formats (see app/Actions/Formatters for examples) and add them into the formats array
in config/posts.php.

You also get a bare basic admin interface. This was thrown together quickly with Tailwindcss and uses zero JavaScript.
You can also override what to use in your template by pointing the LBLOG_ADMIN_ROUTES environment variable to a
different route file.

A simple API with token-based authentication (via sanctum), for playing with things like SPAs. For details on that,
check out tests/Features/API.

Finally the lblog:go artisan command I mentioned above. This is a quick way to reset the system with new posts. When you
do that, the most recent post will always be a sample HTML post, which can be used for trying out things like theming.

**3<sup>rd</sup> party packages**

I’ve tried to keep this as vanilla Laravel as possible but did include a couple of packages. First up are the developer
essentials of [debugbar](https://github.com/barryvdh/laravel-debugbar)
and [IDE Helper](https://github.com/barryvdh/laravel-ide-helper). Then there
is [Intervention](http://image.intervention.io/) to handles the image sizing. Finally (and certainly not least),
is [Spatie’s Query Builder](https://spatie.be/docs/laravel-query-builder/v3/introduction).

For the front end, the only thing included is [Tailwin](https://tailwindcss.com/)d.

**Future of this project.**

There is no current plans to add anything else, but I do keep it up to date with changes in Laravel, mostly rewriting
between major releases. The branches will be named by Laravel major version.

Of course, there might be other things that could be added to help out with the rapid sandbox. If you happen to think of
any, I’m very open to PR requests and discussions. I just want something quick, dirty and easy our community can use to
play with.
