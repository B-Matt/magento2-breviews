 Magento 2 bReviews Extension
==========================

This extension adds features to your Magento 2 store at product review page!
With this extension you can see total score of reviews and review score summary. It removes nickname section in a add review form but extension takes customer name and put it instead (if guest send review it will put "Guest" instead of username).
 
## Requirements

```
Magento 2.0+
```

## How to install

Installation is easy. You can use [Composer](https://getcomposer.org/)or you can put all files from GitHub direcly to your folders. In your code folder create two more folders "Matej\bReviews" and run command:
```php bin/magento setup:upgrade ```. Your folder structure should look like this:
```
app/code/
├── Matej/
│   │   ├──bReviews/
│   │   │   ├── ...
│   │   │   ├── ...
```

## Note
This code is pretty much automate without admin code because this code is made by young developer at his internship at [Inchho](http://www.inchoo.net/) coding in Magento 2 framework for a 2 weeks. This code is practice for this framework and it may not work like you expect nor have good practice code like in professional extensions!
Thanks for looking at this extension! =)