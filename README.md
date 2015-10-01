# CakePHP Log Plugin

A plugin to log some request info and related database changes. Including:

* Model Info
* Model Options (Save)
* Request Info
* Auth Info

## Installation

You may install the Log Plugin through [Composer](http://getcomposer.org) or
[download](https://github.com/andtxr/cakephp-log/archive/master.zip) the source.

### Composer

``composer require andtxr/cakephp-log``

### Source

[Download](https://github.com/andtxr/cakephp-log/archive/master.zip) the source
and unpack it contents inside ``/Plugin/Log``

## Activation

Add the following to your ``Config/bootstrap.php``:

```php
    CakePlugin::load('Log');
```

Load the plugin schema file through your CLI:

```
    cake schema update --plugin Log
```

You may import the ``Config/Schema/log.sql`` file too.

## Usage

Add the following behavior to your ``Model/AppModel.php``:

```php
    public $actsAs = array('Log.Log');
```

You may set the ``userFields`` param to choose which fields of the authenticated
user shall be saved:

```php
    public $actsAs = array(
        'Log.Log' => array(
            'userModels' => array('Admin'),
            'userFields' => array('id', 'name')
        )
    );
```

### Accessing the log data

You may create some actions to retrieve your log information:

```php
    public function index()
    {
        $this->loadModel('Log.Log');
        $this->set('logs', $this->Paginator->paginate('Log'));
    }

    public function view($id)
    {
        $this->loadModel('Log.Log');
        $this->set('log', $this->Log->findById($id));
    }
```
