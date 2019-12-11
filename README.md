# Person Lister Plugin


## Setup and installation
* **Install [Node 8.12.0 LTS or greater](https://nodejs.org)**
* **Install [Yarn](https://yarnpkg.com/en/docs/install)** (Or use npm if you prefer)

## Usage
* Install required modules: `yarn` (or `npm install`)
* Build development version of app and watch for changes: `yarn build` (or `npm run build`)
* Build production version of app:`yarn prod` (or `npm run prod`)

## Explanation

Mixture of both [WP Reactivate](https://github.com/gopangolin/wp-reactivate) 
and [Create Guten Blocks](https://github.com/ahmadawais/create-guten-block) starter
packages. [WP Reactivate](https://github.com/gopangolin/wp-reactivate)  used for 
simple React & Wordpress REST API patterns and [Create Guten Blocks](https://github.com/ahmadawais/create-guten-block)
used for pattern for creating guten blocks.

*webpack.config.js*
```javascript
entry: {
   'js/blocks': path.resolve(__dirname, 'app/blocks.js'),
   'js/personslist': path.resolve(__dirname, 'app/personslist.js')
},
```

Bundling blocks for creating guten blocks on editor's side.  
Bundling personslist for front-end jsx showing. Used dynamic blocks. Save function on block   
 returns null


*includes/Block.php*
```php
wp_register_style( 'person_block_style', 'https://cdn.jsdelivr.net/npm/semantic-ui@2.4.2/dist/semantic.min.css' );
```

Semantic UI have been used for UI framework. It works great with React and easy to make custom
user interfaces.

```php
register_block_type(
	'wrp-person-list/person-container', array(
		// Enqueue blocks.build.js in the editor only.
		'editor_script'   => 'react_lifecycle_block-cgb-block-js',
		'editor_style'    => 'person_block_style',
		'style'           => 'person_block_style',
		'render_callback' => function ( $attribute ) {
			wp_register_script( 'block-script', plugins_url( 'wpr-person-list/assets/js/personslist.js' ));
			wp_localize_script( 'block-script', 'wpr_object', $attribute );
			wp_enqueue_script( 'block-script' )
			return '<div id="render-person-block-container"></div>';
		}
	)
);
```

Simply returning dynamic block on ``div`` with id of ``render-person-block-container``. Id is being selected and 
rendered on React Dom.

## Finalize Work

I have tried to create some pattern that separates View from Business Logic. Custom Post
Types are being used for creating Person model. These model objects are transferred into guten block
using Wordpress Rest API. On Guten blocks, with the usage of dynamic blocks, the attributes are
transferred into dynamic block and then again these attributes are transferred to React DOM using
``wp_localize_script`` function.

## Technologies
| **Tech** | **Description** |
|----------|-------|
|  [React](https://facebook.github.io/react/)  |   A JavaScript library for building user interfaces. |
|  [Babel](http://babeljs.io) |  Compiles next generation JS features to ES5. Enjoy the new version of JavaScript, today. |
| [Webpack](http://webpack.js.org) | For bundling our JavaScript assets. |
| [ESLint](http://eslint.org/)| Pluggable linting utility for JavaScript and JSX  |