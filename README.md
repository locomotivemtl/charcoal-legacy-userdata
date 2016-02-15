UserData for Charcoal Legacy
============================

A module that provides a collection of traits and objects for user-related metadata.

The objects extend core-features with Charcoal Properties implemented by the traits.

Based on the [`UserData` paradigm](https://github.com/locomotivemtl/charcoal-base/tree/master/src/Charcoal/Object#userdata-objects) used by the new Charcoal framework.

> Example of `UserData` objects would be _subscriptions to newsletter_, _blog comments_, _answers to a survey_, _contact form data_, etc.

## Supported Interfaces

**Basics**

* `Core_Trait_User_Metadata`
* `Core_Trait_User_Identity`
* `Core_Trait_User_Organization`
* `Core_Trait_User_Address`
* `Core_Trait_User_Rating_Polarity`

**Objects**

* `Charcoal_User_Entry_Log`
  * Extends `Charcoal_Log`
  * Uses `Core_Trait_User_Metadata`
* `Charcoal_User_Rating`
  * Extends `Charcoal_User_Entry_Log`

## Installation

#### With Composer

```shell
$ composer require locomotivemtl/charcoal-userdata
```

```json
{
	"require": {
		"locomotivemtl/charcoal-userdata": "@dev"
	}
},
"repositories": [
	{
		"type": "vcs",
		"url": "https://github.com/locomotivemtl/charcoal-legacy-userdata"
	}
]
```

#### With SVN

```shell
$ cd www/modules
$ svn propset svn:externals . "cms-meta https://github.com/locomotivemtl/charcoal-legacy-userdata/trunk"
```

#### With Git

```shell
$ git submodule add https://github.com/locomotivemtl/charcoal-legacy-userdata www/modules/userdata
```

## Usage

