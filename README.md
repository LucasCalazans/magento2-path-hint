# Magento 2 - Alias to path hint
Enable path hint with the get param "enablepath"

> http://magento2.dev/customer/account?enablepath

![](path-hint.gif)

If you want the block's name put enablepath=1

> http://magento2.dev/customer/account?enablepath=1

![](path-hint-block.gif)

# Installation

To install the module is just you run the follow command:

```
$ composer require lucas-calazans/module-path-hint
$ bin/magento module:enable LucasCalazans_PathHint
```
