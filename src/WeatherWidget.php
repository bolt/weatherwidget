<?php

declare(strict_types=1);

namespace Bolt\WeatherWidget;

use Bolt\Widget\BaseWidget;
use Bolt\Widget\CacheAwareInterface;
use Bolt\Widget\CacheTrait;
use Bolt\Widget\Injector\AdditionalTarget;
use Bolt\Widget\Injector\RequestZone;
use Bolt\Widget\StopwatchAwareInterface;
use Bolt\Widget\StopwatchTrait;
use Bolt\Widget\TwigAwareInterface;
use Symfony\Component\HttpClient\HttpClient;

class WeatherWidget extends BaseWidget implements TwigAwareInterface, CacheAwareInterface, StopwatchAwareInterface
{
    use CacheTrait;
    use StopwatchTrait;

    protected $name = 'Weather Widget';

    protected $target = AdditionalTarget::WIDGET_BACK_DASHBOARD_ASIDE_TOP;

    protected $priority = 200;

    protected $template = '@weather-widget/weather.html.twig';

    protected $zone = RequestZone::BACKEND;

    protected $cacheDuration = 1800;

    protected $location = '';

    public function run(array $params = []): ?string
    {
        $weather = $this->getWeather();

        if (empty($weather)) {
            return null;
        }

        return parent::run([
            'weather' => $weather,
        ]);
    }

    private function getWeather(): array
    {
        $url = 'https://wttr.in/' . $this->getLocation() .  '?format=%c|%C|%h|%t|%w|%l|%m|%M|%p|%P';

        $curlOptions = $this->getExtension()->getBoltConfig()->get('general/curl_options', [])->all();
        $curlOptions['timeout'] = 6;

        $details = [];

        try {
            $client = HttpClient::create();
            $result = $client->request('GET', $url, $curlOptions)->getContent();
            if (mb_substr_count($result, '|') === 9) {
                $details = explode('|', mb_trim($result));
            }
        } catch (\Throwable $e) {
            dump($this->getName() . ' exception: ' . $e->getMessage());
            // Do nothing, fall through to empty array
        }

        return $details;
    }

    private function getLocation(): string
    {
        if (! $this->extension) {
            return '';
        }

        return (string) $this->extension->getConfig()->get('location');
    }
}
