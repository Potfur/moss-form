# Moss form

**Form** is a simple form abstraction for [MOSS framework](https://github.com/potfur/moss) as completely independent library.

## Form

Each form is a field set (extends Fieldset), that can contain many fields or even other field sets.
To create empty form type:

	$form = new Form('some/url', 'post', array('id' => 'foo'), 'multipart/form-data');

`post`, `attributes` and `enctype` arguments are optional.

`Form` has only three own methods, rest is inherited from `Fieldset`:

 * `action` - allows to retrieve and change forms `action` property
 * `method` - for managing its `method` property - by default its `post`
 * `enctype` - for `enctype` attribute, default is `multipart/form-data`

## Fieldset

`Fieldset` represents a collection of fields and as such is (by default) represented as unordered list with fields as list elements.
Tags - `ul` and `li` can be changed with `groupTag` and `elementTag`.

	$fieldset = new Fieldset('sample', array($field1, $field2), array('id' => 'foo'));

Each fieldset (same goes for `Form` which extends `Fieldset`) provides set of methods:

 * `label` - sets fieldset `legend` tag, if not set - tag will not appear,
 * `attributes` - returns `AttributeBag` instance,
 * `prototype` - returns `Fieldset` with all contents as string that can be used as attribute for dynamic forms,
 * `groupTag` - sets group tag, default value `ul`,
 * `elementTag` - sets element tag, default value `li`
 * `identify` - sets/returns `Fieldset` identifier,
 * `isVisible` - returns true (`Fieldset` is always visible),
 * `isValid` - returns true if `Fieldset` is valid,
 * `errors` - return `ErrorBag` instance,
 * `render` - renders `Fieldset` and returns it as string,
 * `get` - returns field instance with requested id,
 * `set` - sets field - adds or overwrites existing one with set id,
 * `has` - returns true if field with set id exists,
 * `remove` removes field with set id,
 * `all` - returns everything as an array
 * `reset` - resets `Fieldset` removing all fields
 * `__toString` - same as above
 
`Fieldset` also implements `\ArrayAccess`, `\Iterator` and `\Countable`
 
## Fields

Each field implements `FieldInterface`

 * `label` - sets field label, by default its same as fields name
 * `name` - sets field name
 * `value` - sets field value
 * `required` - sets if field is required
 * `condition` - adds condition for validation
 * `validate` - validates field, returns true or false
 * `attributes` - returns `AttributeBag`
 * `renderLabel` - renders field label and returns it as string,
 * `renderField` - renders field,
 * `renderError` - renders all errors (if there are any),
 * `identify` - sets/returns field identifier,
 * `isVisible` - returns true if field is visible,
 * `isValid` - returns true if field is valid,
 * `errors` - return `ErrorBag` instance,
 * `render` - renders label, field and any errors and returns them as string
 * `__toString` - same as above

### Attributes

All form elements have attributes that are represented as `AttributeBag` instance.

 * `get` - gets attribute value, if not set - `null`
 * `set` - sets attribute value, some attributes require array values (eg. `class`) other are scalars,
 * `has` - returns true if attribute is set for element
 * `add` - adds value to attribute, if attribute is scalar, overwrites it
 * `remove` - removes attribute, can also remove value from array attributes (eg. class)
 * `all` - returns all attributes as an array,
 * `render` - renders attributes as string,
 * `__toString` - same as above,
 * `reset` - removes all attributes

`AttributeBag` also implements `\ArrayAccess`, `\Iterator` and `\Countable`

## Validation

Each field has `condition` method, that allows to add validation conditions to it.
Fields value is validated against those conditions every time `validate` method is called.
  
Validation is easy - each condition can be:
 
 * `boolean` - is used only for testing and will be used as validation result, 
 * `string` - every string will treated as regular expression and value will be matched against it,
 * `array` - array containing valid values,
 * `callable` - function, class,
 
	$field = new Text('id', 'foo');
	$errors = $field->condition(array('bar', 'yada'), 'Must contain foo or yada')
		->validate()
		->errors()
		->all(); 
		
Also, its worthy to notice that if field has not been set to `required`, no validation will be made when no value is set.

`Forms` and `Fieldsets` work a little bit different.
`Fieldset` is valid only when all fields and other `Fieldsets` inside are valid - therefore `Form` is valid only when everything inside is valid.

### Errors

Errors are held in `ErrorBag` that contains all errors for set field or fieldset.
`ErrosBag` has same interface as `AttributeBag`.
 
### Options

Options for `Select`, `SelectMultiple`, `Checkbox` and `Radio` fields are represented as `Option` and `OptionBag`.
This allows for easy manipulation - multiple `Option` instances are stored in `OptionBag` in lets say `Select` field.

Also - `Option` has its own `OptionBag` instance - this means that `Option` can contain _suboptions_.

# Example

	<?php
	use Cms\Entity\Author;
	use Moss\Form\Field\Anchor;
	use Moss\Form\Field\Hidden;
	use Moss\Form\Field\Radio;
	use Moss\Form\Field\Submit;
	use Moss\Form\Field\Text;
	use Moss\Form\Fieldset;
	use Moss\Form\Form;
	use Moss\Form\Option;
	
	class SampleUserForm extends Form
	{
	    public function __construct($action, array $entity, $cancel = null)
	    {
	        parent::__construct($action, 'post', '', array());
	
	        $this
	            ->set('id', new Hidden('entity[id]', $entity['id']))
	            ->set('name', new Text('entity[name]', $entity['name'], 'Name', true, array('class' => 'medium')))
	            ->set('visible', new Radio('entity[active]', $entity['active'], 'Active', true, array('class' => 'inline'), $this->getBooleanOptions()));
	
	        $ops = new Fieldset();
	        $ops->attributes()->add('class', 'inline');
	        $ops->set('submit', new Submit('submit', 'submit', 'Save'));
	        $ops->set('cancel', new Anchor('Cancel', $cancel, array('class' => array('button', 'cancel'))));
	
	        $this->set('ops', $ops);
	    }
	
	    public function isValid()
	    {
	        $this
	            ->get('name')
	            ->condition('/^.{6,}$/imu', 'Name must have at least 6 characters');
	
	        $this
	            ->get('visible')
	            ->condition(array(0, 1), 'Choose Yes/No');
	
	        return parent::isValid();
	    }
	
	    protected function getBooleanOptions()
	    {
	        return array(
	            new Option('Yes', 1),
	            new Option('No', 0)
	        );
	    }
	}

