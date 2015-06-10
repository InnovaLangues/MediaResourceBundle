# MediaResourceBundle
This Bundle is intended to be used with Claroline Connect (See https://github.com/claroline/Claroline)

## Requirements

This bundle uses avconv (libav-tools package) to encode uploaded files. So you'll need it on your server.

## Installation

Install with composer :

   $ composer require innova/media-resource-bundle
   
   $ php app/console claroline:plugin:install InnovaMediaResourceBundle

Create a folder named mrfiles in web/upload directory


## Authors

* Donovan Tengblad (purplefish32)
* Axel Penin (Elorfin)
* Arnaud Bey (arnaudbey)
* Eric Vincent (ericvincenterv)
* Nicolas Dufour (eldoniel)
* Patrick Guillou (pitrackster)

## Javascript librairies
Intensive use of the wonderful library [wavesurfer.js] (http://www.wavesurfer.fm/)

