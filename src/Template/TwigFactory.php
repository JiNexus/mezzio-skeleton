<?php

namespace App\Template;

use Interop\Container\ContainerInterface;
use Mezzio\Router\RouterInterface;
use Mezzio\Template\Twig;
use Twig_Environment as TwigEnvironment;
use Twig_Extension_Debug as TwigExtensionDebug;
use Twig_Loader_Filesystem as TwigLoader;

class TwigFactory
{
    public function __invoke(ContainerInterface $container)
    {
        $config = $container->get('config');

        // Create the engine instance
        $loader = new TwigLoader(['templates']);
        $environment = new TwigEnvironment($loader, [
            'cache' => ($config['debug']) ? false : $config['templates']['cache_dir'],
            'debug' => $config['debug'],
            'strict_variables' => $config['debug'],
            'auto_reload' => $config['debug']
        ]);

        // Add extensions
        $environment->addExtension(new TwigExtension(
            $container->get(RouterInterface::class),
            $config['templates']['assets_url'],
            $config['templates']['assets_version']
        ));

        if ($config['debug']) {
            $environment->addExtension(new TwigExtensionDebug());
        }

        // Inject environment
        $twig = new Twig($environment, $config['templates']['extension']);

        // Add template paths
        foreach ($config['templates']['paths'] as $path => $namespace) {
            $twig->addPath($path, $namespace);
        }

        return $twig;
    }
}
