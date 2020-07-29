This script takes advantage of a service from [Bibliogram](https://bibliogram.art), which offers a view of Instagram public profiles. Some of the instances of Bibliogram also offer an RSS feed of the public profile.

I use the feed to build a Micropub request for WithKnown, thus enabling [PESOS](https://indieweb.org/PESOS) from InstaGram.

You need to put your own details into `config-example.php` and then save it as `config.php`.

The script reads the xml feed, converts that to `JSON` and then discards elements that are not an `item` in the feed. It then discards all those older than the previous post, the publication date of which is stored in `pubdate.txt`.

For each new item, it constructs a cURL POST using the information in the item and your WithKnown credentials and sends it to your Known installation.

Despite some problems, notably occasional error messages from WithKnown that are not in fact errors, it works. I take the precaution of storing the publication time of the previous successful post in a backup file, `oldpubdate.txt`.

The script as written may not be very robust, as it depends on `strpos()` to find the Description and the photo URL. Eventually, I hope to improve that.

At the moment I am running this once or twice a day by hand to see how things develop. I am not a very frequent poster to InstaGram, so that is no problem. I’m leaning towards running it maybe four times a day as a `cron` job, and would welcome other suggestions.
