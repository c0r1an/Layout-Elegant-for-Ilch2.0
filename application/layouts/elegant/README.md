# Elegant* Layout for Ilch 2.0

Elegant* is a premium editorial layout for Ilch 2.0 with a required companion module.

The layout and the module belong together:

- `application/layouts/elegant`
- `application/modules/elegant`

The layout handles the visual layer.
The module handles settings, homepage builder logic and the dedicated `Elegant*` boxes.

## Features

- configurable branding (name, tagline, logo), colors and max content width
- configurable hero slider with up to 3 slides
- optional split-image slides (left + right media)
- configurable platform cards with icon picker, text and links
- configurable feature card row (up to 4 cards, visibility options)
- configurable social widget and footer icons
- configurable video widget (YouTube, Vimeo, MP4, Embed URL)
- integrated contact widget based on the Contact module
- independent homepage builder for `/index.php/elegant/index`
- custom frontend styles for article, forum, vote, gallery and user login boxes
- layout-specific module view overrides in `views/modules/...`

## Installation

1. Copy the `elegant` folder into `application/layouts/`.
2. Copy the companion module `elegant` into `application/modules/`.
3. Install the module in the Ilch admin area.
4. Install or activate the layout.
5. Open `Admin -> Elegant*` and configure the settings and homepage builder.

## Menu Usage

- Menu 1 is used as the main navigation.
- Menu 2 is used as sidebar/widget boxes.

## Notes

- This layout contains module view overrides inside `views/modules/...`.
- Layout settings are defined through the companion module and rendered in `Admin -> Elegant* -> Einstellungen`.
- The module homepage builder controls `/index.php/elegant/index` independently from the normal start page logic.

## Export

Run the PowerShell script below from inside the `elegant` folder to build a distributable ZIP:

```powershell
powershell -ExecutionPolicy Bypass -File .\export-layout.ps1
```

The archive is written to `dist/elegant-v<version>.zip`.

To build a GitHub-friendly bundle containing both layout and module in Ilch folder structure:

```powershell
powershell -ExecutionPolicy Bypass -File .\export-github.ps1
```

This archive is written to `dist/elegant-github-v<version>.zip`.

## License

This project is licensed under the MIT License. See `LICENSE`.
