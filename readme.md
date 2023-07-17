#### Overview

A Laravel package that lets you generate boilerplate code for a Laravel app. Simply enter the name of a database table and based on that it will create:

- A Laravel model
- A Laravel controller (with get, list, create, update, delete as well as validation based on a chosen DB table)
- Laravel routes (get, list, create, update, delete)

This package aims to speed up the process of communicating between backend (Laravel) and frontend (Vue.js).

### Installation

`composer require torkzz/generate-laravel`

## Usage

Firstly you should create a new migration in the same way that you usually would. For example if creating a posts table use the command

`php artisan make:migration create_posts_table`

Then in your migration file add your fields as usual

```
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title',200);
    $table->text('content')->nullable();
    $table->timestamps();
});
```

Then run the migrate command to create the posts table

`php artisan migrate`

Once you have done that you just need to run one `zz:laravel` command. Add the name of your table to the end of the command so in this case it's posts.

`php artisan zz:laravel posts`

This will then generate all the files mentioned above.

Once you have run this command, using the `posts` example above, it will create the following boilerplate files:

### Routes

Based on a `posts` DB table it will produce these routes

```
Route::get('posts', 'PostsController@list');
Route::get('posts/{id}', 'PostsController@get');
Route::post('posts', 'PostsController@create');
Route::put('posts/{id}', 'PostsController@update');
Route::delete('posts/{id}', 'PostsController@delete');

```

### Controller

Based on a `posts` DB table it will produce this controller

```
<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Posts;

class PostsController extends Controller
{
    public function get(Request $request, $id){
      return Posts::findOrFail($id);
    }

    public function list(Request $request){
      return Posts::get();
    }

    public function create(Request $request){

      $validatedData = $request->validate([
        'title' => 'required |max:200 ',
        'content' => 'required ',
        'meta_description' => 'required |max:160 ',
      ],[
        'title.required' => 'title is a required field.',
        'title.max' => 'title can only be 200 characters.',
        'content.required' => 'content is a required field.',
        'meta_description.required' => 'meta_description is a required field.',
        'meta_description.max' => 'meta_description can only be 160 characters.',
      ]);

        $posts = Posts::create($request->all());
        return $posts;
    }

    public function update(Request $request, $id){

      $validatedData = $request->validate([
        'title' => 'required |max:200 ',
        'content' => 'required ',
        'meta_description' => 'required |max:160 ',
      ],[
        'title.required' => 'title is a required field.',
        'title.max' => 'title can only be 200 characters.',
        'content.required' => 'content is a required field.',
        'meta_description.required' => 'meta_description is a required field.',
        'meta_description.max' => 'meta_description can only be 160 characters.',
      ]);

        $posts = Posts::findOrFail($id);
        $input = $request->all();
        $posts->fill($input)->save();
        return $posts;
    }

    public function delete(Request $request, $id){
        $posts = Posts::findOrFail($id);
        $posts->delete();
    }
}
 ?>

```

### Model

Based on a `posts` DB table it will produce this model

```
<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class Posts extends Model
{

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var  array
     */
    protected $guarded = [
        'id'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var  array
     */
    protected $casts = [
        ''
    ];
}?>

```

## Configuration

Here are the configuration settings with their default values.

```
<?php
return [
    'model_dir' => base_path('app/Models'),
    'controller_dir' => base_path('app/Http/Controllers'),
    'routes_file' => 'api.php'
];
?>
```

To copy the config file to your working Laravel project enter the following artisan command

`php artisan vendor:publish --provider="torkzz\generateLaravelApi\generateInitialServiceProvider" --tag="config"`

##### model_dir

Specifies the location where the generated model files should be stored

#### controller_dir

Specifies the location where the generated controller files should be stored

#### routes_dir

Specifies the location of the routes directory

#### routes_file

Specifies the name of the routes file

### Customising the templates

If you use another frontend framework such as React or you want to adjust the structure of the templates then you can customise the templates by publishing them to your working Laravel project

`php artisan vendor:publish --provider="torkzz\generateLaravelApi\generateInitialServiceProvider" --tag="templates"``

They will then appear in

`\resources\views\vendor\generateLaravelApi`

### Variables in the templates

Each template file passes a data array with the following fields

##### $data['singular']

The singular name for the DB table eg Post

##### $data['plural']

The plural name for the DB table eg Posts

##### $data['singular_lower']

The singular name for the DB table (lowercase) eg post

##### $data['plural_lower']

The plural name for the DB table eg (lowercase) eg posts

##### $data['fields']

An array of the fields that are part of the model.

- name (the field name)
- type (the mysql varchar, int etc)
- simplified_type (text, textarea, number)
- required (is the field required)
- max (the maximum number of characters)
