# Elegant* Module for Ilch 2.0

The `elegant` module is the companion module for the `Elegant*` layout.

It provides:

- the dedicated admin area under `Admin -> Elegant*`
- the layout settings renderer used by the layout
- the homepage builder for `/index.php/elegant/index`
- reusable `Elegant*` boxes such as slider, intro, platform cards, feature cards, news box, contact widget, social widget and video widget

## Installation

1. Copy the folder `elegant` into `application/modules/`.
2. Copy the layout folder `elegant` into `application/layouts/`.
3. Install the module in the Ilch admin area.
4. Activate the layout.

The layout expects this module to be installed for the full settings and homepage-builder workflow.

## Admin Areas

- `Admin -> Elegant* -> Einstellungen`
- `Admin -> Elegant* -> Startseite`

## Homepage Builder

The builder manages the module route:

- `/index.php/elegant/index`

This route is intentionally separate from the normal layout homepage logic.

## License

MIT. See `LICENSE`.
