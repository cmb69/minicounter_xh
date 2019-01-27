# Minicounter\_XH

Minicounter\_XH ermöglicht es, die Besucher Ihrer Website zu zählen. Es
ist ein sehr minimalistischer Zähler, der nur die Gesamtanzahl der
Besucher zählt, aber dies konnte in einer sehr effizienten Weise
implementiert werden, indem die nötigen Information temporär in einem
Sitzungscookie gespeichert werden.

## Inhaltsverzeichnis

  - [Voraussetzungen](#voraussetzungen)
  - [Installation](#installation)
  - [Einstellungen](#einstellungen)
  - [Verwendung](#verwendung)
  - [Einschränkungen](#einschränkungen)
  - [Lizenz](#lizenz)
  - [Danksagung](#danksagung)

## Voraussetzungen

Minicounter\_XH ist ein Plugin für CMSimple\_XH ≥ 1.6.3. Es benötigt PHP
≥ 5.5.0.

## Installation

Die Installation erfolgt wie bei vielen anderen CMSimple\_XH-Plugins
auch. Im
[CMSimple\_XH-Wiki](http://www.cmsimple-xh.org/wiki/doku.php/de:installation)
finden Sie weitere Details.

1.  Sichern Sie die Daten auf Ihrem Server.
2.  Entpacken Sie die ZIP-Datei auf Ihrem Rechner.
3.  Laden Sie das ganze Verzeichnis `minicounter/` auf Ihren Server in
    CMSimple\_XHs `plugin/` Verzeichnis hoch.
4.  Machen Sie die Unterverzeichnisse `config/` und `languages/`
    beschreibbar.
5.  Navigieren Sie zu *Plugins* → *Minicounter* im
    Administrationsbereich, um zu prüfen, ob alle Voraussetzungen
    erfüllt sind.

## Einstellungen

Die Plugin-Konfiguration erfolgt wie bei vielen anderen
CMSimple\_XH-Plugins auch im Administrationsbereich der Website. Wählen
Sie *Plugins* → *Minicounter*.

Sie können die Voreinstellungen von Minicounter\_XH unter
*Konfiguration* ändern. Hinweise zu den Optionen werden beim Überfahren
der Hilfe-Icons mit der Maus angezeigt.

Die Lokalisierung wird unter *Sprache* vorgenommen. Sie können die
Sprachtexte in Ihre eigene Sprache übersetzen, falls keine entsprechende
Sprachdatei zur Verfügung steht, oder diese Ihren Wünschen gemäß
anpassen.

## Verwendung

Um den Zähler auf Ihrer Website anzuzeigen fügen Sie auf einer
CMSimple-Seite ein:

    {{{minicounter()}}}

oder im Template:

    <?=minicounter()?>

Sie können den angezeigten Text in der Plugin-Administration unter
*Sprache* ändern. Bitte beachten Sie, dass der Text so gewählt wurde, da
Minicounter\_XH nicht die eigentliche Besucherzahl anzeigt, sondern eine
feste Ordinalzahl für jeden Besucher vergibt. Sie können diesen Effekt
sehen, wenn Sie Ihre Website mit zwei verschiedenen Browsern aufrufen.

Besuche im Adminmodus (einschließlich Login und Logout) werden nicht
gezählt. Darüber hinaus ist es möglich IP-Adressen vollständig per
Konfigurationsoption auszuschließen.

Es gibt keine Einstellung für die maximale Inaktivitätsdauer eines
Besuchers, so dass er weiterhin als der selbe Besucher angesehen wird.
Dies wird automatisch durch die Verwendung von Sitzungscookies geregelt.
So lange die Sitzung aktiv ist, wird der Besucher als der selbe
angesehen. Die Sitzung endet, wenn der Browser geschlossen wird.

## Einschränkungen

Die Verwendung von Besucher-Trackern kann aus Datenschutzgründen durch
Gesetze eingeschränkt oder gar ganz verboten sein. Sie können
Minicounter\_XH so konfigurieren, dass das DNT (Do Not Track) Flag
berücksichtigt wird, was ausreichen könnte. Im Zweifel sollten Sie
einen Anwalt befragen.

Minicounter\_XH ist nicht genau\! In der Tat ist es grundsätzlich nicht
möglich Besucher einer Website genau zu zählen. Das kommt daher, dass es
im allgemeinen unmöglich ist Besucher zuverlässig zu identifizieren. Von
Aufruf zu Aufruf kann sich deren IP-Adresse ändern, und mehrere Besucher
können die gleiche IP Adresse haben, wenn sie sich hinter einem Proxy
befinden. Und im Laufe der Zeit ändern sich IPv4 Adressen sowieso.
Andere Techniken wie Browser-Fingerprinting und Tracking-Bilder sind
ebenfalls nicht 100% verlässlich. Der Sizungscookie basierte Ansatz, den
Minicounter\_XH verwendet, ist vermutlich noch ungenauer, da er nur
funktioniert, wenn das Sitzungscookie vom Besucher akzeptiert wird.

## Lizenz

Minicounter\_XH kann unter Einhaltung der
[GPLv3](http://www.gnu.org/licenses/gpl.html) verwendet werden.

Copyright © 2012-2019 Christoph M. Becker

Slovakische Übersetzung © 2012 Dr. Martin Sereday  
Dänische Übersetzung © 2012 Jens Maegard

## Danksagung

Das Plugin-Logo wurde von [Yusuke Kamiyamane](http://www.pinvoke.com/)
entworfen. Vielen Dank für die Veröffentlichung unter CC BY-SA.

Vielen Dank an die Gemeinschaft im
[CMSimple\_XH-Forum](http://www.cmsimpleforum.com/) für Tipps,
Vorschläge und das Testen.

Und zu guter letzt vielen Dank an [Peter Harteg](http://www.harteg.dk/),
den "Vater" von CMSimple, und allen Entwicklern von
[CMSimple\_XH](http://www.cmsimple-xh.org/de/) ohne die es dieses
phantastische CMS nicht gäbe.
