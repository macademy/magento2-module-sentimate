<h1 align="center">Macademy_Sentimate</h1> 

<div align="center">
  <p>Runs sentiment analysis on reviews & more.</p>
  <img src="https://img.shields.io/badge/magento-2.4.4-brightgreen.svg?logo=magento&longCache=true&style=flat-square" alt="Supported Magento Versions" />
  <a href="https://packagist.org/packages/macademy/magento2-module-sentimate" target="_blank"><img src="https://img.shields.io/packagist/v/macademy/magento2-module-sentimate.svg?style=flat-square" alt="Latest Stable Version" /></a>
  <a href="https://packagist.org/packages/macademy/magento2-module-sentimate" target="_blank"><img src="https://poser.pugx.org/macademy/magento2-module-sentimate/downloads" alt="Composer Downloads" /></a>
  <a href="https://github.com/macademy/magento2-module-sentimate/pulse/monthly" target="_blank"><img src="https://img.shields.io/badge/maintained%3F-yes-brightgreen.svg?style=flat-square" alt="Maintained - Yes" /></a>
  <a href="https://opensource.org/licenses/MIT" target="_blank"><img src="https://img.shields.io/badge/license-MIT-blue.svg" /></a>
</div>

## Table of contents

- [Summary](#summary)
- [Why](#why)
- [Installation](#installation)
- [Usage](#usage)
- [Sponsor](#sponsor)
- [License](#license)

## Summary

This module runs sentiment analysis on product reviews from customers. It uses RabbitMQ message queues to call an external API provided by RapidAPI that retrieves the sentiment analysis of the product review. The sentiment analysis is then displayed before the "Review" text on the product detail page when viewing reviews.

![Demo](https://raw.githubusercontent.com/macademy/magento2-module-sentimate/master/docs/demo.png)

## Why

Why should you use this module? This provides additional feedback to customers that explicitly lets them know the sentiment of each and every review.

## Installation

```
composer require macademy/magento2-module-disabletwofactorauth
bin/magento module:enable Macademy_Sentimate
bin/magento setup:upgrade
```

## Usage

This module requires a [RapidAPI](https://rapidapi.com/auth/sign-up?referral=/twinword/api/sentiment-analysis) API Key to use the [Twinword Sentiment Analysis API](https://rapidapi.com/twinword/api/sentiment-analysis/).

Once you have a RapidAPI API Key, visit Admin -> Stores -> Configuration -> Catalog -> Sentimate, and enter in your API Key. Remember to clear the cache after entering in your API Key.

You can start the Sentimate queue consumer to process reviews by typing:

```
bin/magento queue:consumers:start macademy.sentimate.reviews
```

## Sponsor

This module was built and published as part of the [Magento Message Queues with RabbitMQ](https://m.academy/rabbitmq/) course, created by M.academy. If you want to know how this module was built, check out the course!

## License

[MIT](https://opensource.org/licenses/MIT)
