# Digital signature verified autoloader

This Composer plugin modifies the autoloader Composer generates so that at
load time, files not already compiled into the PHP opcache are verified to be
un-tampered with.

In general, this kind of model makes sense when the autoloader's files have some
more restrictive permissions than the files they load.

Currently a work in progress.

To sign code for use with the autoloader, use the [signature-verified-generator](https://github.com/curator-wik/composer-signature-verified-generator)
plugin.
