![](https://www.seven.io/wp-content/uploads/Logo.svg "seven Logo")

# seven for CS-Cart

## Prerequisites

- A working CS-Cart installation 4.11.1+ (tested with CS-Cart 4.14.x)
- An [API key](https://help.seven.io/en/api-key-access) from [seven](https://www.seven.io)

## Installation

1. Download
   the [latest release](https://github.com/seven-io/cscart/releases/latest/download/seven-cscart-latest.zip)
   as *.ZIP archive
2. Open your CS-Cart administration, then go to `Add-ons -> Manage add-ons` and click on
   the `+` icon
3. The `Upload & install add-on` window should appear. Click on `Local`, select the
   downloaded *.ZIP file from your computer and press `Upload & install`

## Setup

1. Go to `Administration -> Send SMS -> Settings`
2. Set your API key from your [seven developer dashboard](https://app.seven.io/developer)
3. Submit by clicking `Save`

## How to send SMS?

This addon offers multiple ways to send SMS to your customers.

### Send a test message

1. Go to `Administration -> Send SMS -> SMS Test`
2. Fill out the form and submit (TODO: add screenshot)

### Send SMS after the status of an order changed

1. In the `Manage` window click `Message templates`
2. Check the checkbox of the message you want to send and fit his template

### Send Bulk SMS

1. Go to `Administration -> Send SMS -> Bulk SMS`
2. Fill the fields below (TODO: add screenshot)

### Send SMS to customer associated with an order

1. Go to `Administration -> Orders -> View orders`
2. Load an order by clicking `Order #`
3. Click the `Add-ons` tab, fill out the seven SMS form and click `Send SMS`

## Message History

You can find an overview of sent messages by navigating
to `Administration -> Send SMS -> SMS Logs`.

## Support

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/).

[![MIT](https://img.shields.io/badge/License-MIT-teal.svg)](LICENSE)
