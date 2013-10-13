# Form for moss microframework

## Form

To create empty form type:

	$Form = new Form('some/url');

Each form is a field set (extends Fieldset), that can contain many fields or even other field sets.
`Form` adds three methods to fieldset:

 * `action` - allows to retrieve and change forms `action` property
 * `method` - for managing its `method` property - by default its `post`
 * `enctype` - for `enctype`, default value its `multipart/form-data`

## Fieldset

`Fieldset` represents a collection of fields. A form is a fieldset, fields regarding personal data or even group of checkboxes can be represented as `Fieldset`.
`Fieldset` is represented as unsorted list, where each element represents single field.

Each fieldset (same goes for `Form` which extends `Fieldset`) provides set of methods:

 * `label` - changes and returns fieldset label (default: null, rendered as legend tag)
 * `attributes` - returns `AttributesBag`
 * `all` - returns all fields
 * `set` - adds or overwrites field
 * `get` - retrieves field by its identifier
 * `remove` - remove field with set identifier
 * `prototype` - renders `Fieldset` as _prototype_, HTML escaped string with twig/smarty tags intact (for data-prototype property similar to [Symfony 2 form collections](http://symfony.com/doc/current/cookbook/form/form_collections.html#allowing-new-tags-with-the-prototype)
 * `identify` - returns identifier
 * `isValid` - returns `true` if all fields are valid
 * `errors` - returns collection of all errors from all fields in set
 * `render` - renders `Fieldset` as string

## Fields

## Options

## Attributes

## Errors

