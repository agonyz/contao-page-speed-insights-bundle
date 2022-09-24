# Page Speed Insights Bundle
## Extension for the [Contao CMS](https://www.contao.org)

This extension can be used to automatically track the [page speed insights](https://pagespeed.web.dev/) for your root domains.

For a Quick-Setup follow this link: [Guide-For-Quick-Setup](docs/QuickSetup.md)

## Installation
Run ```composer require agonyz/contao-page-speed-insights-bundle``` in your CLI to install the extension.

## Configuration
To use the [Page Speed Insights API](https://pagespeed.web.dev/) you need to register an [Api Key](https://developers.google.com/speed/docs/insights/v5/get-started?hl=de).   
You can edit the configuration in your ```config/config.yml```

```yml
# config/config.yml
# Agonyz Page Speed Insights
agonyz_contao_page_speed_insights:
  api_key: your-secret-api-key
  request_retries: 3
  pool_request_concurrency: 10
  request_status_refresh_rate: 5000
```

Please remember to always clear the cache after each change in the ```config.yml```.   
It may be also needed to delete the cached results via command after changes in the config file.

## Choose which pages to track
In the root page configuration navigate to ```Page Speed Insights Bundle``` and tick the checkbox.

## Commands & Cronjob
### Commands
- ```agonyz-page-speed-insights:request``` - generates new request results and saves them to the database

### Cronjob
You can either implement your own cronjob or use contao cron

#### Via Command
- Create a cronjob that executes the ```agonyz-page-speed-insights:request``` - Command

#### Via Cron
- Configurate ```Agonyz\ContaoPageSpeedInsightsBundle\Cron\PageSpeedInsightsCron``` in your ```config/services.yml```

Example:
```yml
# Cronjob
services:
  Agonyz\ContaoPageSpeedInsightsBundle\Cron\PageSpeedInsightsCron:
    tags:
      -
        name: contao.cronjob
        interval: '0 */24 * * *'
    arguments:
      [ '@contao.framework', '@Agonyz\ContaoPageSpeedInsightsBundle\Service\Request\RequestHandler']
```
Documentation: https://docs.contao.org/dev/framework/cron/#using-service-tagging


## Page Speed Insights
In the backend navigate to the ```Pagespeed-Insights``` - entry point under ```SYSTEM```.   

![psibundle](docs/page_speed_insights_bundle.png?raw=true "psibundle")
