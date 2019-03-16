# ActivityLog

[![Codacy Badge](https://api.codacy.com/project/badge/Grade/dc3819bf2c654b3d8dcaaed8898b214f)](https://www.codacy.com/app/laravel-enso/ActionLogger?utm_source=github.com&amp;utm_medium=referral&amp;utm_content=laravel-enso/ActionLogger&amp;utm_campaign=Badge_Grade)
[![StyleCI](https://styleci.io/repos/85554059/shield?branch=master)](https://styleci.io/repos/85554059)
[![License](https://poser.pugx.org/laravel-enso/activitylog/license)](https://packagist.org/packages/laravel-enso/activitylog)
[![Total Downloads](https://poser.pugx.org/laravel-enso/activitylog/downloads)](https://packagist.org/packages/laravel-enso/activitylog)
[![Latest Stable Version](https://poser.pugx.org/laravel-enso/activitylog/version)](https://packagist.org/packages/laravel-enso/activitylog)

Activity logger dependency for [Laravel Enso](https://laravel-enso.com).

[![Watch the demo](https://laravel-enso.github.io/activitylog/screenshots/bulma_051_thumb.png)](https://laravel-enso.github.io/activitylog/videos/bulma_activity_log.mp4)

<sup>click on the photo to view a short demo in compatible browsers</sup>


### Features

- friendly interface for viewing user activity in the application
- by default only available to users with the Administrator role
- events are presented in an useful manner
- allows the filtering of data depending on a date interval, the roles of the users, the users or the type of events
- supports create, update, delete and custom event types
- the models whose changes need to represented, need only to use the `LogsActivity` trait. Optionally, 
you may set additional configuration attributes on the model to further fine tune the way data is logged/represented. 

### Configuration & Usage

Be sure to check out the full documentation for this package available at [docs.laravel-enso.com](https://docs.laravel-enso.com/backend/activity-log.html)

### Contributions

are welcome. Pull requests are great, but issues are good too.

### License

This package is released under the MIT license.
