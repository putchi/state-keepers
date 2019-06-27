# state-keepers

State keepers for Laravel application

Any exception caught inside the closure will be handled and if necessary will rollback to previous app state so that no partial data will be stored on your Database.  

### Prerequisites

This package is an extension to Laravel and will work only on laravel 5.7 and up-words 

### Installing

Clone or [Download](https://github.com/putchi/state-keepers/archive/master.zip) this [repo](https://github.com/putchi/state-keepers) 
or install it by using [composer](https://getcomposer.org/) bellow:

```
composer require putchi/state-keepers
```

You may publish project files (config, assets, translations, etc...) into your own project 
by running the bellow command:

```
php artisan vendor:publish --provider="StateKeepersServiceProvider"
```

Also you may need to clear config cache by running the following command:

```
php artisan config:cache
```

If you have trouble installing this package please view the list of issues [here](https://github.com/putchi/state-keepers/issues) or open a new one.

### Example of usage

```
return StateManager::guard(function () {
  // Call some function in some model...
  // #  e.g: return Model::someFunction($arg1, $arg2);
  // Side note: don't forget to use whatever parameters you want in the declaration of the anonymous function (above)...
  // #  e.g: function () use ($arg1, $arg2) {...}
}, function (\Throwable $exception) {
  // HERE WE CATCH THE ERROR (IF THERE WAS ONE)
  // you can use the $exception to show the error massage like: $exception->getMessage();
  // Or you can implement your own customized catch callback in here.
});
```

## Contributing

You may [fork](https://github.com/putchi/state-keepers/fork) this repo or suggest new features (or new keepers) via pull requests and by following our code of conduct guide bellow.

Please read [CONTRIBUTING.md](https://gist.github.com/putchi/2e513d67249d6ea407da1bbaef1b8022) for details on our code of conduct, and the process for submitting pull requests to us.

[Watch](https://github.com/putchi/state-keepers/subscription) this project for changes, status or version updates.

## Versioning

We use [SemVer](http://semver.org/) for versioning. For the versions available, see the [tags on this repository](https://github.com/putchi/state-keepers/tags). 

## Authors

* **Alex Rabinovich** - *Initial work* - [Putchi](https://github.com/putchi)

See also the list of [contributors](https://github.com/putchi/state-keepers/contributors) who participated in this project.

If you liked our work we did here please give us the thumbs up by [staring](https://github.com/putchi/state-keepers) this repo.


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

