# Minicounter\_XH

Minicounter\_XH facilitates counting the visitors of your website. It's
a very minimalistic counter, that will only count the total number of
visitors, but this could be implemented in a very efficient manner by
storing the required information in a session cookie.

## Table of Contents

  - [Requirements](#requirements)
  - [Installation](#installation)
  - [Settings](#settings)
  - [Usage](#usage)
  - [Limitations](#limitations)
  - [License](#license)
  - [Credits](#credits)

## Requirements

Minicounter\_XH is a plugin for CMSimple\_XH ≥ 1.6.3. It requires PHP ≥
5.5.0.

## Installation

The installation is done as with many other CMSimple\_XH plugins. See
the [CMSimple\_XH
wiki](http://www.cmsimple-xh.org/wiki/doku.php/installation) for further
details.

1.  Backup the data on your server.
2.  Unzip the distribution on your computer.
3.  Upload the whole directory `minicounter/` to your server into
    CMSimple\_XH's `plugins/` directory.
4.  Set write permissions to the subdirectories `config/` and
    `languages/`.
5.  Browse to *Plugins* → *Minicounter* in the back-end to check if all
    requirements are fulfilled.

## Settings

The plugin's configuration is done as with many other CMSimple\_XH
plugins in the website's administration area. Select *Plugins* →
*Minicounter*.

You can change the default settings of Minicounter\_XH under *Config*.
Hints for the options will be displayed when hovering over the help icon
with your mouse.

Localization is done under *Language*. You can translate the character
strings to your own language if there is no appropriate language file
available, or customize them according to your needs.

## Usage

To display the counter on your website insert on a CMSimple page:

    {{{minicounter()}}}

or in the template:

    <?=minicounter()?>

You can change the displayed text in the plugin administration under
*Language*. Please note the text was chosen this way, because
Minicounter\_XH doesn't display the actual visitor count, but insteads
keeps a fixed number for each visitor. You can see the effect, if you
access your site with two different browsers.

Visits in admin mode (including login and logout) are not counted.
Furthermore it's possible to exclude IP addresses completely in the
configuration.

There's no setting how long a visitor may be inactive and still be
counted as the same visitor. All that happens transparently through the
use of session cookies. As long as the session is active, the visitor
will be regarded the same. The session ends when the browser is closed.

## Limitations

The usage of visitor trackers may be restricted or even prohibited by
laws for privacy concerns. You can configure Minicounter\_XH to honor
the DNT (Do Not Track) flag, what might be sufficient. If in doubt,
consult a lawyer.

Minicounter\_XH is not accurate\! In fact it is not possible to
accurately count visitors on a website at all. That stems from the fact
that in general it's impossible to identify visitors reliably. From
request to request their IP addresses may change, or multiple visitors
may use the same IP address if they sit behind a proxy for instance, and
over time IPv4 addresses will change anyway. Other techniques such as
browser fingerprinting and tracking images are not 100% reliable either.
The session cookie based approach used by Minicounter\_XH is most likely
even more inaccurate, as it will work only if the session cookie is
accepted by the visitor.

## License

Minicounter\_XH is licensed under
[GPLv3](http://www.gnu.org/licenses/gpl.html).

Copyright © 2012-2019 Christoph M. Becker

Slovak translation © 2012 Dr. Martin Sereday  
Danish translation © 2012 Jens Maegard

## Credits

The plugin logo has been designed by [Yusuke
Kamiyamane](http://www.pinvoke.com/). Many thanks for publishing this
icon under CC BY-SA.

Many thanks to the community at the
[CMSimple\_XH-Forum](http://www.cmsimpleforum.com/) for tips,
suggestions and testing.

And last but not least many thanks to [Peter Harteg](http://harteg.dk/),
the "father" of CMSimple, and all developers of
[CMSimple\_XH](http://www.cmsimple-xh.org/) without whom this amazing
CMS wouldn't exist.
