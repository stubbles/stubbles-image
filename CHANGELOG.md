# Changelog

## 7.0.0 (2023-12-26)

### BC breaks

* Raised minimum required PHP version to 8.2 and switched all `resource` type hints to `GdImage`
* Image instances can't be created with `new Image()` any more:
  * either load an existing image with `Image::load()`
  * or create a new image with `Image::create()`
* Requires PHP extension fileinfo
* Automatic driver selection for images is now based on mimetype of actual file if file exists
* `stubbles\img\Driver::display()` is now defined as returning `void`

## 6.2.0 (2019-11-26)

* Added support for jpeg images with new `stubbles\img\driver\JpegDriver`
* Added more details to exception messages when storing or loading an image fails
* Passing no driver to constructor of `stubbles\img\Image` will now select driver based on extension in filename, and fallback to png when extension is unknown or not present

## 6.1.0 (2019-11-18)

### BC breaks

* Added new method `contentForDisplay($handle): string` to interface `stubbles\img\driver\ImageDriver`
* Changed signature of `stubbles\img\driver\ImageDriver::display()` to return type `void`

### Other changes

* Added `stubbles\img\Image::contentForDisplay(): string` to return image contents ready for display

## 6.0.0 (2019-11-12)

### BC breaks

* raised minimum required PHP version to 7.3

## 5.0.0 (2016-07-31)

### BC breaks

* raised minimum required PHP version to 7.0.0
* introduced scalar type hints and strict type checking
* removed `stubbles\image\driver\DummyImageDriver::reset()`, not required

## 4.0.0 (2014-09-01)

### BC breaks

* removed `stubbles\image\response`, is now in stubbles/webapp-image
* removed `stubbles\img\Image::loadFromResource()`
* removed `stubbles\img\ImageType`, `stubbles\img\Image` now works directly with drivers
* removed all methods deprecated with 3.0.0 (see below)

### Other changes

* removed dependency to stubbles/core 5.x and stubbles/webapp-core 5.x

## 3.0.0 (2014-08-03)

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
* added `stubbles\img\response\ImageFormatter`

## 2.0.4 (2014-01-23)

* ensured that ext-gd is present

## 2.0.3 (2013-11-04)

* ensured that response body is empty when image present so nothing gets send after image was sent

## 2.0.2 (2013-11-04)

* ensured that `net\stubbles\img\response\ImageResponse::clear()` also removes image

## 2.0.1 (2013-09-11)

* Composer cleanup

## 2.0.0 (2013-09-11)

* Initial release.
