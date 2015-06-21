# Datadiff PHP Class

This is a simple PHP class that creates a curl client and sends a commit to the [Datadiff](http://datadiff.co) API.

## Installation

You can `git clone` this repo or simply copy and paste the datadiff.php file.

Make sure you have curl and php-curl installed.

## Usage

1. Create a [Datadiff](https://datadiff.co) account.
2. Create a data source and obtain it's API credentials.
3. Include the PHP class.
4. Execute a commit to the Datadiff API.

The PHP class sends a commit to the API via the `commit` method with the following arguments:

1. The data model (object)
2. The name of the collection/table
3. The command (created|updated|deleted)
4. The unique ID field. i.e `_id` for Mongo.
5. A meta data object (optional)


## Committing an update to the Datadiff API

In this example a customer has just updated their account.

```
// account has been updated
$account->update();

// initialise the Datadiff object and send a commit
Datadiff::factory($client_id, $client_secret)->commit($account, 'accounts', 'updated', '_id', array('action'=>'updated account', 'src' => 'accounts page on software'));
```
