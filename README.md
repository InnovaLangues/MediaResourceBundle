# MediaResourceBundle
This Bundle is intended to be used with [Claroline Connect LMS] (https://github.com/claroline/Claroline)

## Requirements
- This bundle uses avconv (libav-tools package) to encode uploaded files. So you'll need it on your server.
- GoogleTTS Api is also used for backward building help. This functionality will only work on Chrome.

## Installation

Install with composer :

   $ composer require innova/media-resource-bundle
   
   $ php app/console claroline:plugin:install InnovaMediaResourceBundle

Create a folder named innovamediaresourcefiles at the root of the application & set appropriate rights on it.

## Authors

* Donovan Tengblad (purplefish32)
* Axel Penin (Elorfin)
* Arnaud Bey (arnaudbey)
* Eric Vincent (ericvincenterv)
* Nicolas Dufour (eldoniel)
* Patrick Guillou (pitrackster)

## Javascript librairies
Intensive use of the wonderful library [wavesurfer.js] (http://www.wavesurfer.fm/)

## TODO
Create AdditionalInstaller script to simplify plugin installation.

