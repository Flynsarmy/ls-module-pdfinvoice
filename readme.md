# PDF Invoice

This plugin adds PDF invoice functionality to LemonStand.

## Installation
* `cd /path/to/lemonstand/modules`
* `git clone https://github.com/Flynsarmy/ls-module-pdfinvoice.git flynsarmypdfinvoice`
* `cd flynsarmypdfinvoice`
* `composer install`
* Log out and back in
* Create a page */download-invoice-pdf* with action `flynsarmypdfinvoice:generate_invoice_pdf`
* Go to */download-invoice-pdf/some-order-id*