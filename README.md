<p align="center">
  <img src="https://www.seven.io/wp-content/uploads/Logo.svg" width="250" alt="seven logo" />
</p>

<h1 align="center">seven SMS for CS-Cart</h1>

<p align="center">
  Single, bulk and event-based SMS for <a href="https://www.cs-cart.com/">CS-Cart</a> via the seven gateway.
</p>

<p align="center">
  <a href="LICENSE"><img src="https://img.shields.io/badge/License-MIT-teal.svg" alt="MIT License" /></a>
  <img src="https://img.shields.io/badge/CS--Cart-4.11%2B-blue" alt="CS-Cart 4.11+" />
  <img src="https://img.shields.io/badge/PHP-7.4%2B-purple" alt="PHP 7.4+" />
</p>

---

## Features

- **Test SMS** - Send single test messages from the admin
- **Order-Based SMS** - Notify the customer when an order status changes via configurable templates
- **Bulk SMS** - Broadcast to all customers
- **Per-Order SMS** - Send a one-off SMS from inside an order detail page
- **Message History** - Browse outgoing SMS in *SMS Logs*

## Prerequisites

- A working [CS-Cart](https://www.cs-cart.com/) 4.11.1+ installation (tested with 4.14.x)
- A [seven account](https://www.seven.io/) with API key ([How to get your API key](https://help.seven.io/en/developer/where-do-i-find-my-api-key))

## Installation

1. Download the [latest release](https://github.com/seven-io/CS-Cart/releases/latest/download/seven-cscart-latest.zip).
2. In the CS-Cart admin go to **Add-ons > Manage add-ons** and click the **+** icon.
3. In the *Upload & install add-on* dialog, click **Local**, pick the downloaded ZIP and press **Upload & install**.

## Configuration

1. Go to **Administration > Send SMS > Settings**.
2. Paste your seven API key.
3. Click **Save**.

## Usage

### Send a test SMS

Open **Administration > Send SMS > SMS Test**, fill the form and submit.

### Order-status based SMS

1. In *Manage* click **Message templates**.
2. Tick the message you want to enable and edit the template body.

### Bulk SMS

Open **Administration > Send SMS > Bulk SMS** and fill the form.

### Per-order SMS

1. Open **Administration > Orders > View orders**.
2. Open an order via **Order #**.
3. Click the **Add-ons** tab, fill the seven SMS form and click **Send SMS**.

### History

Outgoing SMS are listed under **Administration > Send SMS > SMS Logs**.

## Support

Need help? Feel free to [contact us](https://www.seven.io/en/company/contact/) or [open an issue](https://github.com/seven-io/CS-Cart/issues).

## License

[MIT](LICENSE)
