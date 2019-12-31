<?php

namespace App\Template;

use Mezzio\Router\RouterInterface;

class TwigExtension extends \Twig_Extension
{
    /**
     * @var \Mezzio\Router\RouterInterface
     */
    private $router;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * @var string
     */
    private $assetsUrl;

    /**
     * @var string
     */
    private $assetsVersion;

    public function __construct(
        RouterInterface $router,
        $assetsUrl,
        $assetsVersion
    ) {
        $this->router = $router;
        $this->assetsUrl = $assetsUrl;
        $this->assetsVersion = $assetsVersion;
    }

    public function getName()
    {
        return 'mezzio';
    }

    public function getFunctions()
    {
        return [
            new \Twig_SimpleFunction('path', [$this, 'renderUri']),
            new \Twig_SimpleFunction('asset', [$this, 'renderAssetUrl']),
        ];
    }

    /**
     * Usage: {{ path('name', parameters) }}
     *
     * @param $name
     * @param array $parameters
     * @param bool $relative
     * @return string
     */
    public function renderUri($name, $parameters = [], $relative = false)
    {
        return $this->router->generateUri($name, $parameters);
    }

    /**
     * Usage: {{ asset('path/to/asset/name.ext', version=3) }}
     *
     * @param $path
     * @param null $packageName
     * @param bool $absolute
     * @param null $version
     * @return string
     */
    public function renderAssetUrl($path, $packageName = null, $absolute = false, $version = null)
    {
        $assetUrl = $this->assetsUrl . $path;

        if ($version) {
            $assetUrl .= '?v=' . $version;
        } elseif ($this->assetsVersion) {
            $assetUrl .= '?v=' . $this->assetsVersion;
        }

        return $assetUrl;
    }
}
