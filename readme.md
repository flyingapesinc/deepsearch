## DeepSearch for Laravel

DeepSearch is a search package for laravel that use recursivity to efficiently find a record of a model, by occurrences of any word found in the search and in any relationship given for the model.

Let's say you have a model Post, the model Post hasMany comments, and the model Comment belongsTo a User. Beside, your app user searched for the string "Galactic Empire,and... pizza for steve".

Yup, that makes sense. If you want to find a post given that string in any part of the relationship chain of the Post model it could, for example:

* bring a post which title is "The love for pizza".
* bring a post that has a comment that says "Wow that was out of this empire"
* bring a post that has a comment posted by a guy named "Steve". Hi Steve.

### Installation

Require the package in your composer.json and update composer to download the package

	composer require flyingapesinc/deepsearch

After that, add the ServiceProvider to the providers array in config/app.php

```php
FlyingApesInc\DeepSearch\ServiceProvider::class,
```

if you want to, add the facade for convenience

```php
'DeepSearch' => FlyingApesInc\DeepSearch\Facade::class,
```

## How to Use

DeepSearch::find() will bring back your search results. It takes 3 arguments:

* __$search__ the search string to find
* __$model__ the model from which you want to return the records. ex: 'App\Post'
* __$searchModels__ an array with the relations of the main model you want to search in, as well as what fields. The format is as follows:

```php
$searchModels = [
    'searchFields' => ['title'], // Fields where you want to search in the main model
    'relations' => [ // Relationships, if any
        [
            'relationship' => 'comments', // Here you put name of the relationship
            'searchFields' => ['comment'], // And here the fields where you want to search in the related table
        ],
        [
            'relationship' => 'comments.user', // Use dot notation for inner relations
            'searchFields' => ['name'],
        ]
    ]
];
```

The find() method returns a query, so you need to bring the results by yourself using get() or paginate(n). Following the example above we get:

```php
$search = DeepSearch::find($userInput, 'App\Post', $searchModels)->get();
```

## Authors

* __lHumanizado__
* __Rubenazo__
