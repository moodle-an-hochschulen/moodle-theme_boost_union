Smart menu providers
====================

This is a documentation for plugin developers who want to add further locations or presentation types to the smart menus of Boost Union.


Overview
--------

A smart menu in Boost Union has two aspects which are realized by providers:

* The locations of the menu define where the menu is shown on the page. Boost Union ships the location providers `main` (main navigation), `menu` (menu bar) and `user` (user menu).
* The presentation type of the menu defines how the items of the menu are presented within the menu. Boost Union ships the presentation provider `list`.

Everything else is done by Boost Union itself: The settings pages, the storing of the menus and menu items, the access rules, the caching and the building of the menu and menu item nodes.

Moodle themes cannot have subplugins. That's why additional providers are shipped by ordinary plugins (preferably of the type `local`) which register their providers with a hook of Boost Union.


Registering a provider
----------------------

### Hook callback

A provider is a class which extends `\theme_boost_union\smartmenu\location\base` or `\theme_boost_union\smartmenu\presentation\base`. Add a hook callback to the db/hooks.php file of your plugin:

```php
$callbacks = [
    [
        'hook' => \theme_boost_union\hook\smartmenu\collect_providers::class,
        'callback' => 'local_example\local\hook\collect_providers::callback',
    ],
];
```

In the callback, add your providers:

```php
public static function callback(\theme_boost_union\hook\smartmenu\collect_providers $hook): void {
    $hook->add_presentation(new \local_example\smartmenu\presentation\tiles());
    $hook->add_location(new \local_example\smartmenu\location\bottombar());
}
```

### Provider skeleton

The provider class itself lives in your plugin, for example in classes/smartmenu/presentation/tiles.php:

```php
namespace local_example\smartmenu\presentation;

class tiles extends \theme_boost_union\smartmenu\presentation\base {
    [...]
}
```

### Key and name

Each provider has a key (`get_key()`) which identifies it within its kind and which is stored in the database. The key has to consist of lowercase letters and digits only, has to start with a letter and has to be 2 to 50 characters long. Providers with invalid keys or with keys which are already used are ignored with a debugging message. The keys of the built-in providers cannot be overridden.

In addition to the key, each provider has a name (`get_name()`). While the key is the technical identifier (which the admin only gets to see if a menu uses a provider which is not available anymore), the name is what the admin sees normally: It is shown as option in the "Menu location(s)" setting or in the "Presentation type" setting of the smart menu edit form and as badge in the list of smart menus. Return a localised string from the language pack of your plugin here. The name is not stored anywhere, so you can change it at any time.

```php
public function get_key(): string {
    // Valid keys: 'tiles', 'tiles2', 'mytiles'.
    // Invalid keys: 'Tiles' (uppercase letter), 'my_tiles' (underscore), '2tiles' (starts with a digit),
    // 't' (too short), 'list' (key of a built-in provider).
    return 'tiles';
}

public function get_name(): string {
    return get_string('presentationtilesname', 'local_example');
}
```

Please note: As the key is stored in the database with each menu which uses your provider, you must not change it anymore as soon as your plugin has been released. Otherwise, the existing menus would not find your provider anymore.

### Dependency on Boost Union release and API version

Declare the dependency on Boost Union in the version.php file of your plugin, with the Boost Union version which introduced the API version you are building on.

Furthermore, the Boost Union provider API is versioned with the constant `\theme_boost_union\smartmenu\provider::API_VERSION`. This number is raised whenever the API is changed in a way which is not compatible with existing providers. Changes to the API are announced in the CHANGES.md file of Boost Union.

Each provider has to declare the API version which it has been built for in `get_api_version()`. Return a fixed number there (currently `1`), do not return the constant of Boost Union. If this number differs from the API version of the installed Boost Union, the provider is ignored with a debugging message until it has been adapted, and the menus which use it are handled as described above for providers which are not available.

```php
public function get_api_version(): int {
    // Right: The API version which this provider has been built and tested for.
    return 1;

    // Wrong: This would always match the installed Boost Union, even if the API has been changed
    // in the meantime and this provider has not been adapted yet.
    // return self::API_VERSION;
}
```

### Providers which are not available

If a menu uses a provider which is not available (anymore), for example as its plugin has been uninstalled, the menu falls back to the list presentation or is not shown at the missing location. The menu keeps the key and the settings of the missing provider and is flagged in the list of smart menus.



Settings of a provider
----------------------

### Form elements

A provider can add form elements to the smart menu edit form (`menu_form_definition()`) and to the smart menu item edit form (`item_form_definition()`).

Please note that `menu_form_definition()` is a protected function. Overwrite it as protected function in your provider as well. It is not called directly by the menu form, but by `add_menu_form_elements()` of the provider base class, which is the function which hides your elements as long as your provider is not selected (see below).

* All form elements have to be named with `$this->field_name('myfield')` to avoid collisions.
* In the menu form, the elements of all providers are added. Boost Union hides the elements which your provider adds in `menu_form_definition()` automatically as long as your provider is not selected, you do not have to care about that. This works for all elements with a name (and for groups as a whole). Elements without a name (like plain HTML elements) cannot be hidden, so avoid them or wrap them into a named group. You can still add `hideIf()` rules of your own to your elements, for example to make one of your elements depend on another one.
* In the item form, only the providers which are used by the menu of the item are asked for their elements.

### Validation

Validate your elements in `menu_form_validation()` and `item_form_validation()`. Return the errors keyed by the prefixed element names, so that they are shown directly at your elements. In the menu form, only the providers which are selected in the submitted form are asked to validate their elements. In the item form, these are the providers which are used by the menu of the item.

### Stored settings

Boost Union stores the settings of a provider as JSON in the menu or the menu item, keyed by the key of the provider. The settings of locations are stored in the field `locationdata`, the settings of presentations are stored in the field `presentationdata`. You can read the stored settings of your provider from a menu or menu item record with `get_data()`. By default, the values of all your form elements are stored and are loaded into the form again. A provider does not need database tables of its own.

The settings of a provider are kept when the admin selects another presentation type or removes the menu from a location, so that they are not lost when the admin switches back.

### Files and other settings which need special handling

If you need more control (for example for file managers or editors), override `menu_form_prepare_data()` / `menu_form_process_data()` and `item_form_prepare_data()` / `item_form_process_data()`. The process functions are called after the menu or the menu item has been saved, so the ID of the record is known and can be used as item ID of a file area.

Files have to be stored in a file area of your own plugin (in the system context), not in a file area of Boost Union. Your plugin has to serve them with its own `pluginfile` callback and has to delete them in the `menu_deleted()` / `item_deleted()` callbacks.

### Lifecycle callbacks

The lifecycle callbacks `menu_deleted()`, `item_deleted()`, `menu_duplicated()` and `item_duplicated()` allow you to clean up or to copy data which you store outside of the provider data, like files.


Combining presentations and locations
-------------------------------------

### Restricting the combinations

A menu has exactly one presentation and one or more locations. Not every presentation makes sense at every location: A presentation which needs a lot of space might not work in a narrow location, and a location with a fixed layout might only be able to show plain lists. That's why both kinds of providers can restrict the providers of the other kind which they can be combined with:

* A presentation declares the locations at which it can be shown with `get_supported_locations()`.
* A location declares the presentations which can be used for its menus with `get_supported_presentations()`.

Both functions return a list of provider keys or `null`. `null` is the default and means that all providers of the other kind are supported. Please note that a list is a positive list: A provider which is added by another plugin later is not supported until you add its key. So, return a list only if your provider really cannot handle the other providers.

```php
// A presentation which cannot be shown in the menu bar and in the locations of other plugins.
public function get_supported_locations(): ?array {
    return ['main', 'user'];
}

// A location which can only show plain lists.
public function get_supported_presentations(): ?array {
    return ['list'];
}
```

### Enforcing the combinations

A presentation can be used at a location only if both sides agree, i.e. if the presentation supports the location and if the location supports the presentation. You can check a combination with `\theme_boost_union\smartmenu\provider_registry::is_supported_combination()`. Boost Union enforces the combinations at two places:

* In the menu form, a menu cannot be saved if its presentation cannot be used at one of its locations. The admin sees an error message at the location field.
* When a menu is rendered and its presentation cannot be used at a location nevertheless (for example as a provider changed its supported providers after the menu has been saved or as the menu has been created with a generator), the menu is shown as list at this location. At its other locations, it is still shown with its presentation.

### Inline mode

In addition to that, both kinds of providers can declare with `menu_supports_inline_mode()` whether their menus can be shown in inline mode (where the menu items are shown directly instead of within a submenu). The default is `false`, so a provider has to overwrite the function and return `true` to confirm that it handles the inline mode (as all built-in providers do). This is handled differently for the two kinds of providers:

* For a presentation which does not support the inline mode, the menu mode setting is hidden in the menu form and the menus are always shown as submenu.
* For a location which does not support the inline mode, a menu in inline mode cannot be saved in the menu form. If such a menu exists nevertheless (for example as the provider changed its inline mode support after the menu has been saved or as the menu has been created with a generator), it is not shown at this location and a developer debugging message is issued. At its other locations, it is still shown.


Presentation providers
----------------------

A presentation provider can influence the rendering of a menu in two steps:

* At build time, `decorate_menu_node()` and `decorate_item_node()` can add data or CSS classes to the nodes of the menu and of its items. The decorated nodes are cached per user together with the menu, so do not add anything which depends on the current page.
* At render time, `render_menu_content()` can return HTML which is shown instead of the list of the menu items. This HTML is used in the React based main navigation and menu bar (including menus which have been collapsed into the "More" menu), in the fallbacks of these two locations for browsers without JavaScript and in the locations of other plugins. If the function returns null, the menu is rendered as list by the location.
* There are two exceptions on purpose: In the user menu and in the mobile navigation drawer (which is the main navigation on small screens), smart menus are always shown as list, regardless of their presentation, and `render_menu_content()` is not called for them. Both are narrow and are built as lists with submenus by Moodle core, and that's why Boost Union does not offer them as rendering paths for presentations. If your presentation should adapt to small screens in the other rendering paths, use media queries in the SCSS of your presentation.

Please note:

* Presentations which do not support the inline mode are always shown as submenu (see above). The menu is stored with the submenu mode as soon as it is saved in the menu form then.
* With `item_supports_submenu_mode()`, a presentation declares whether it can present menu items in submenu mode (i.e. dynamic courses items which show their courses in a submenu of their own). The default is `false`, so a presentation has to overwrite the function and return `true` to confirm that it handles such items (as the built-in list presentation does). Otherwise, such items are not shown in the menu. The menu item mode setting is not offered in the menu item form for menus with this presentation then, and an existing item in submenu mode is set to the inline mode as soon as it is saved.
* The HTML is not cached and is rendered once per location in which the menu is shown.
* The HTML is output as it is and is not escaped or cleaned by Boost Union in any of the rendering paths: The React based navigation inserts it with `dangerouslySetInnerHTML`, the Mustache templates of the fallbacks for browsers without JavaScript output it with triple curly braces. So, your presentation is responsible for the security of its HTML, just like any other Moodle plugin which renders HTML. Escape everything which comes from the settings of the menu and of the menu items or from other user input, for example with `s()` or `format_string()`, or render the HTML with a Mustache template and use double curly braces there. Clean values which you put into `style` attributes (like colors) strictly before you output them. Please note that the `text` of the item nodes is HTML already (it has been formatted by Boost Union and may contain the icon of the menu item) and must not be escaped again.
* `<script>` tags within the HTML are not executed in the React based navigation, as browsers do not run scripts which are inserted this way. Do not rely on inline scripts or inline event handler attributes, use AMD modules instead (see below).
* The main navigation and the menu bar are always rendered on the server first. This server-side markup is the fallback for browsers without JavaScript, and it is replaced by the React based navigation as soon as JavaScript is running. Both variants show the same HTML of your presentation (Boost Union hands it over as `contenthtml` of the menu node to both of them). So, your HTML has to be usable without any JavaScript of your own, and the JavaScript which you add must only enhance it.
* The main navigation and the menu bar are rendered by React which replaces DOM nodes when the navigation is re-measured. If your content needs JavaScript, return AMD modules in `get_amd_modules()` and use delegated event listeners in their `init()` functions.
* Use links with the class `dropdown-item` and the role `menuitem` for your items so that the keyboard navigation of the dropdowns works.


Location providers
------------------

A location provider gets the smart menu nodes which are assigned to its location and which are visible for the current user in its `export()` function, together with a context object (`\theme_boost_union\smartmenu\location\context`). The location renders the menus to HTML and adds this HTML to one of the template slots of the Boost Union layouts with `$context->add_slot_html()`. The available slots are defined as `SLOT_*` constants in `\theme_boost_union\smartmenu\location\base`: above the navbar, below the navbar, at the top of the page element, above the footer, at the end of the footer and at the end of the page wrapper.

A location only works within its own scope: The template slots are the only way to hand over its output. The data which the primary navigation exports to the layouts (the main navigation, the user menu, the language menu and the output of the other locations) is not exposed to the location on purpose. The context functions `set_primary_navigation()` and `attach_to_user_menu()` are reserved for the built-in main navigation and user menu locations and throw a coding exception if they are called by any other location.

The primary navigation class in `$context->primary` offers helper functions for rendering the menus:

* `build_moremenu_navigation()` builds a navigation bar whose items collapse into a "More" menu, rendered by the same React component as the main navigation. Render its result with the template `core/primarymoremenu`.
* `flag_active_nodes_first()` flags the active nodes based on the current page.
* `apply_presentation_content()` lets the presentations of the menus render their content. `build_moremenu_navigation()` calls it itself. Call it only if you render the nodes on your own without this helper, and show `contenthtml` instead of the children of a node if it is set.

A location can add CSS classes to the body element with `get_body_classes()` and can load AMD modules with `get_amd_modules()`.


Differences between the built-in providers and the providers of other plugins
-----------------------------------------------------------------------------

The built-in providers of Boost Union extend the same base classes and use the same API as the providers of other plugins. However, they extend structures which are owned by Moodle core and by Boost Union itself, and that's why they rely on some mechanisms which are hardwired in Boost Union and which are not available to the providers of other plugins. If you use the built-in providers as examples, you should know these differences:

Registration:

* The built-in providers are instantiated directly by `\theme_boost_union\smartmenu\provider_registry`, they are not added with the `collect_providers` hook. They always come first in the lists of locations and presentation types, the providers of other plugins follow in the order in which their hook callbacks are called.
* The keys `main`, `menu`, `user` and `list` are reserved. A provider of another plugin which uses one of these keys is ignored.
* The built-in providers return `self::API_VERSION` in `get_api_version()` as they are always shipped together with the API. A provider of another plugin has to return a fixed number (see above).
* The keys of the built-in providers are available as constants (`\theme_boost_union\smartmenu::LOCATION_*` and `\theme_boost_union\smartmenu::PRESENTATION_LIST`) and are known to the upgrade steps of Boost Union which migrated the menus from the former data model.

The list presentation:

* The list presentation is the fallback presentation. It is used whenever the presentation of a menu is not available (anymore) or cannot be used at a location. This is hardwired in the registry and cannot be changed by another plugin.
* The list presentation does not render anything itself: Its `render_menu_content()` returns `null`. The list of menu items is rendered by the locations instead, i.e. by the React component and by the Mustache templates of Boost Union for the built-in locations. This means for you: A presentation of another plugin cannot hook into this native rendering, it has to return the complete HTML of the menu content. And a location of another plugin has to render the children of a menu node as list on its own whenever a menu node does not carry `contenthtml`.

The main navigation and the user menu:

* These two locations do not use a template slot. They write the menus into the navigation structures which exist in the data which the primary navigation exports to the layouts anyway (`moremenu` and `mobileprimarynav` for the main navigation, `user` for the user menu). They do this with the context functions `set_primary_navigation()` and `attach_to_user_menu()`. These structures are rendered by templates of Moodle core which Boost Union overrides (for example `core/primarymoremenu` and `core/user_menu`) and by the React component `theme_boost_union/nav/Nav`, which is a modified copy of the navigation component of Moodle core.
* A location of another plugin cannot use these two context functions, they are reserved for these two locations and throw a coding exception otherwise. The main navigation and the user menu are structures of Moodle core, and a location of another plugin is a new place on the page by definition which belongs into a template slot.
* The main navigation is the only location whose menus are rendered twice: In the navigation bar and in the mobile navigation drawer. The presentations of the menus only render their content for the navigation bar, in the mobile navigation drawer the menus are always shown as list. And the main navigation itself is built by Boost Union on every page, even if there is not any smart menu at this location, as it is a part of Moodle core.
* The JavaScript of the user menu smart menus (the AMD module `theme_boost_union/smartmenu_usermenu`) is not loaded with `get_amd_modules()`. It is loaded directly by the user menu template, as it has to be initialised together with the user menu module of Moodle core.

The menu bar:

* The menu bar is the built-in location which is closest to a location of another plugin: It renders its own navigation bar with `build_moremenu_navigation()` and adds it to the template slot above the navbar.
* However, the page layout has to make room for the menu bar. The rules which move the navbar, the drawers and the page content down by the height of the menu bar are part of the SCSS of Boost Union itself, not of the SCSS of the menu bar provider, as they mirror layout rules of Moodle core. A location of another plugin which needs room on the page has to add such rules with `get_post_scss()` and can use the body classes from `get_body_classes()` for them.

All built-in locations:

* The React component of the main navigation and of the menu bar knows the properties of the smart menu nodes (like the content HTML of a presentation, the description, the tooltips and the "More menu behavior" setting of a menu). A location of another plugin can reuse this component with `build_moremenu_navigation()`, but it cannot extend it with properties or components of its own. And settings which are interpreted by this component only (like the "More menu behavior" setting) do not have any effect in a location which renders its menus on its own.
* The "More menu behavior" setting of a menu is only offered in the menu form as long as at least one location is selected which declares with `menu_supports_moremenu_behavior()` that it supports this setting. The default is `false`. The main navigation and the menu bar return `true`, the user menu returns `false` as it does not have a "More" menu. A location of another plugin which builds its menus with `build_moremenu_navigation()` should overwrite the function and return `true`, as the React component handles the setting for it.
* The general styles of the smart menus (below the body class `theme-boost-union-smartmenu`) are part of the SCSS of Boost Union and are tailored to the markup of the built-in locations. Do not expect them to style the markup of your location.
* The Behat steps of Boost Union which check the smart menus in particular locations (for example `I should see smart menu "..." item "..." in location "Main, Menu, User"`) only know the built-in locations. For your location, you have to check the markup of your location with the generic Behat steps or with steps of your own plugin.


Styling
-------

Providers can add SCSS to the SCSS of Boost Union with `get_pre_scss()` (variables) and `get_post_scss()` (rules). The code is compiled together with the theme, so all Bootstrap, Boost and Boost Union variables and mixins are available and the code can follow the color modes of the theme. The post SCSS is added directly after the post SCSS of Boost Union, so the custom SCSS of the admin and the flavours can still override it.

As soon as at least one smart menu is shown on a page, the body element carries the class `theme-boost-union-smartmenu`.


Testing
-------

The Behat generators of Boost Union accept the name of your presentation type and of your locations as they are shown in the edit forms. The settings of your provider can be given as JSON in the `presentationdata` column (or in the `locationdata` column for a location):

```
Given the following "theme_boost_union > smart menu" exists:
  | title            | Card menu                 |
  | location         | Main navigation           |
  | presentation     | Card                      |
  | presentationdata | {"card": {"cardsize": 2}} |
```

Boost Union ships some test locations and test presentations in tests/fixtures/smartmenu/ which are used in its own tests and which can serve as minimal examples: `testlocation` and `testpresentation` cover most parts of the provider API (settings, validation, rendering), `testinlinelocation` and `testinlinepresentation` show how the inline mode of menus is supported and `testsubmenupresentation` shows how the submenu mode of menu items is supported.
