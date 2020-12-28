# Dynamic Eloquent Relationship

Adds dynamic relationship to Eloquent ORM models

### Installation

`composer require i-rocky/eloquent-dynamic-relation`

### Usage

Add the trait `Rocky\Eloquent\HasDynamicRelation` to your model as following

```PHP

use Rocky\Eloquent\HasDynamicRelation;

class MyModel extends Model {
  use HasDynamicRelation;
}
```

Now define a relationship somewhere

In `Laravel` you can add this in your `AppServiceProvider`'s `boot` method

```PHP
MyModel::addDynamicRelation('some_relation', function (MyModel $myModel) {
    return $myModel->hasMany(SomeRelatedModel::class);
});
```


Now you can use the relation `some_relation` as if it's defined in your `MyModel` class.
