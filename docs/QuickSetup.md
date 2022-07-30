# Page Speed Insights Bundle
## QuickSetup

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
```

### Choose which sites should be tracked
In the root page configuration navigate to ```Page Speed Insights Bundle``` and tick the checkbox.

![rootpage](page_speed_insights_root_page.png?raw=true "rootpage")

### Cronjob
```yml
# Cronjob
services:
  Agonyz\ContaoPageSpeedInsightsBundle\Cron\PageSpeedInsightsCron:
    tags:
      -
        name: contao.cronjob
        interval: '0 */2 * * *'
    arguments:
      [ '@contao.framework', '@Agonyz\ContaoPageSpeedInsightsBundle\Service\RequestCacheHandler']
```
Documentation: https://docs.contao.org/dev/framework/cron/#using-service-tagging
