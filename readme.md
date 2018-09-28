# Micro CDN [![Build Status](https://travis-ci.org/lawrencemh/micro-cdn.svg?branch=master)](https://travis-ci.org/lawrencemh/micro-cdn) ![StyleCI](https://github.styleci.io/repos/93316902/shield?branch=master)

This is a micro service built in Lumen allowing the storage and management of image assets via a REST API.

## Getting the application up and running
### Dependencies
First install [Composer](https://getcomposer.org/), if you haven’t
already. Composer manages the third-party dependencies for the
application.

    $ cd ~/bin
    $ curl https://getcomposer.org/installer | php
    $ mv composer.phar composer

Then, to install the dependencies themselves, run Composer:

    $ composer install
    
### Configuration
First, copy the example environment file to `.env`:

    $ cp .env.example .env
    
Then generate a new secret key

    $ php artisan key:generate
    
Edit the `.env` file and set the database details to the correct values
for your environment.

Additionally, you should set the following `.env` keys:
- `CACHE_USER_TOKEN_MINUTES` If wish to cache the user's token to prevent unnecessary database hits.
- `ORIGINAL_IMAGE_QUALITY` This is the quality compression rating for the original sized images that can be compressed.
- `COPIES_QUALITY` This is the quality compression rating for images after they are resized to small, medium and large sizes.
- `FIT_SMALL_COPIES_TO_PX` This is the maximum width/height in pixels to use for small images when they are resized.
- `FIT_MEDIUM_COPIES_TO_PX` This is the maximum width/height in pixels to use for medium images when they are resized.
- `FIT_LARGE_COPIES_TO_PX` This is the maximum width/height in pixels to use for large images when they are resized.

### Running migrations

If you’re running this on a new database:

	$ php artisan migrate
	$ php artisan db:seed
	
### API endpoints
With all requests the user's API token **MUST** be provided with the header key `api-token`.
The following endpoints are available.

#### User Containers `/containers`
Available verbs: `GET`, `POST`, `PATCH`, `DELETE`

##### `microcdn.com/containers` `GET`
This route is used to display all the given user's containers.

##### `microcdn.com/containers/{id}` `GET`
This route is used to display the given container that belongs to the user.

##### `microcdn.com/containers` `POST`
This route can be used to create a new container to store images and requires the intended `name` of the container to be posted.

##### `microcdn.com/containers/{id}` `PATCH`
This route can be used to change the name of an existing container by providing the new `name` in the payload.

##### `microcdn.com/containers/{id}` `DELETE`
This route can be used to permanently destroy a given container as well as any media that belongs to it.


#### User Containers `/containers/{id}/media`
Available verbs: `GET`, `POST`, `PATCH`, `DELETE`

##### `microcdn.com/containers/{id}/media` `GET`
This route is used to display all the media for a given container.

##### `microcdn.com/containers/{id}/media/{id}` `GET`
This route is used to display a given media item for the container in addition to the sized versions of the image.

##### `microcdn.com/containers/{id}/media` `POST`
This route allows you to add a new image to the container by posting a multipart form request with the `media_item` being the file to upload.

##### `microcdn.com/containers/{id}/media/{id}` `PATCH`
This route allows you to add `meta_data` to an existing image item. The `meta_data` should be in array format.

##### `microcdn.com/containers/{id}/media/{id}` `DELETE`
This route allows you to permanently remove a media item from the given container. This will also remove it from storage.

