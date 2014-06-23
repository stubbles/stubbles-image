3.0.0 (2014-06-??)
------------------

### BC breaks

  * removed namespace prefix `net`, base namespace is now `stubbles\img` only
  * api changes
    * renamed methods in `stubbles\image\driver\ImageDriver`:
      * `getExtension()` is now `fileExtension()`
      * `getContentType()` is now `mimeType()`
    * renamed methods in `stubbles\image\driver\DummyImageDriver`:
      * `getLastStoredFileName()` is now `lastStoredFileName()`
      * `getLastStoredHandle()` is now `lastStoredHandle()`
      * `getLastDisplayedHandle()` is now `lastDisplayedHandle()`
    * deprecated `stubbles\img\Image::getName()`, use `stubbles\img\Image::fileName()` instead, will be removed with 4.0.0
    * deprecated `stubbles\img\Image::getType()`, use `stubbles\img\Image::type()` instead, will be removed with 4.0.0
    * deprecated `stubbles\img\Image::getHandle()`, use `stubbles\img\Image::handle()` instead, will be removed with 4.0.0
    * deprecated `stubbles\img\Image::getExtension()`, use `stubbles\img\Image::fileExtension()` instead, will be removed with 4.0.0
    * deprecated `stubbles\img\Image::getContentType()`, use `stubbles\img\Image::mimeType()` instead, will be removed with 4.0.0

### Other changes

  * upgraded to stubbles/core 4.x & stubbles/webapp-core 4.x
  * added `stubbles\img\Image::loadFromResource()`


2.0.3 (2013-11-04)
------------------

  * ensured that response body is empty when image present so nothing gets send after image was sent


2.0.2 (2013-11-04)
------------------

  * ensured that `net\stubbles\img\response\ImageResponse::clear()` also removes image


2.0.1 (2013-09-11)
------------------

  * Composer cleanup


2.0.0 (2013-09-11)
------------------

  * Initial release.
