# [Browsershot](https://github.com/spatie/browsershot) wrapper for Laravel 5
This package takes advantage of Google Chrome's Headless mode to take screenshots and generate PDFs from websites, views and raw html


# Requirements

* [Node](https://nodejs.org/) 7.6.0 or higher
* [Google Chrome](https://www.google.com/chrome/)
* [Puppeteer Node library](https://github.com/GoogleChrome/puppeteer).

You can install Puppeteer in your project via NPM:

```bash
npm install puppeteer
```

Or you could opt to just install it globally

```bash
npm install puppeteer --global
```

On a [Forge](https://forge.laravel.com) provisioned Ubuntu 16.04 server you can install the latest stable version of Chrome like this:

```bash
curl -sL https://deb.nodesource.com/setup_8.x | sudo -E bash -
sudo apt-get install -y nodejs gconf-service libasound2 libatk1.0-0 libc6 libcairo2 libcups2 libdbus-1-3 libexpat1 libfontconfig1 libgcc1 libgconf-2-4 libgdk-pixbuf2.0-0 libglib2.0-0 libgtk-3-0 libnspr4 libpango-1.0-0 libpangocairo-1.0-0 libstdc++6 libx11-6 libx11-xcb1 libxcb1 libxcomposite1 libxcursor1 libxdamage1 libxext6 libxfixes3 libxi6 libxrandr2 libxrender1 libxss1 libxtst6 ca-certificates fonts-liberation libappindicator1 libnss3 lsb-release xdg-utils wget
sudo npm install --global --unsafe-perm puppeteer
sudo chmod -R o+rx /usr/lib/node_modules/puppeteer/.local-chromium
```

# Installation

Install the package through composer 

```bash
composer require verumconsilium/laravel-browsershot
```

After the package is installed the service provider will be automatically discovered and two new Facades `PDF` and `Screenshot` will be available

# Usage

The recommended way to use this package is through its Facades 

## PDF 

### Generating a PDF from a view and returning it inline

```php
  
  use VerumConsilium\Browsershot\Facades\PDF;
  
  ...
  
  return PDF::loadView('view.name', $data)
            ->inline();
  
```

You can chain all the methods available in the [browsershot master library](https://github.com/spatie/browsershot)

### Returning the PDF as a download

```php
  use VerumConsilium\Browsershot\Facades\PDF;
  
  ...
  
  return PDF::loadView('view.name', $data)
            ->margins(20, 0, 0, 20)
            ->download();
```

You can pass the custom file name and additional headers the response will have to the `inline` and `download` methods like

```php
  PDF::loadHtml('<h1>Awesome PDF</h1>') 
      ->download('myawesomepdf.pdf', [
        'Authorization' => 'token'
      ]);
```

### Persisting PDF to disk

If you would like to save the generated pdf file to your storage disk you can call the `store` or `storeAs` method

```php
  $pdfStoredPath = PDF::loadUrl('https://google.com')
                      ->store('pdfs/')
```

This will use the default storage driver to store the pdf in the `pdfs/` folder  giving it a unique name. If you would like to specify the name you can call de `storeAs` method


```php
  $pdfStoredPath = PDF::loadUrl('https://google.com')
                      ->storeAs('pdfs/', 'google.pdf')
```

## Screenshots

Screenshots are created the same way as PDFs just change the facade to `Screenshot`

### Generating screenshots as JPG/JPEG

By default screenshots will be taken as PNG format if you would like to use JPG instead call the `useJPG()` method

```php
use VerumConsilium\Browsershot\Facades\Screenshot;

Screenshot::loadView('view.name', $data)
           ->useJPG()
           ->margins(20, 0, 0, 20)
           ->download();
```
