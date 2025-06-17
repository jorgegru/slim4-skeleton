<?php

declare(strict_types=1);

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;
use Slim\Exception\HttpException;
use Slim\Psr7\Response as SlimResponse;

if (!function_exists('app')) {
    /**
     * Get the container instance or a service from the container.
     *
     * @param string|null $abstract The service identifier to resolve.
     * @return mixed|ContainerInterface
     */
    function app(string $abstract = null)
    {
        global $app;
        
        if (is_null($abstract)) {
            return $app->getContainer();
        }
        
        return $app->getContainer()->get($abstract);
    }
}

if (!function_exists('config')) {
    /**
     * Get a configuration value from the settings.
     *
     * @param string $key The configuration key (dot notation supported).
     * @param mixed $default The default value if the key is not found.
     * @return mixed
     */
    function config(string $key, $default = null)
    {
        $settings = app('settings');
        
        if (is_null($key)) {
            return $settings;
        }
        
        // Support dot notation for nested arrays
        if (strpos($key, '.') === false) {
            return $settings[$key] ?? $default;
        }
        
        $array = $settings;
        foreach (explode('.', $key) as $segment) {
            if (isset($array[$segment])) {
                $array = $array[$segment];
            } else {
                return $default;
            }
        }
        
        return $array;
    }
}

if (!function_exists('json_response')) {
    /**
     * Create a JSON response.
     *
     * @param mixed $data The data to encode as JSON.
     * @param int $status The HTTP status code.
     * @param array $headers Additional headers to include in the response.
     * @return Response
     */
    function json_response($data = null, int $status = 200, array $headers = []): Response
    {
        $response = new SlimResponse();
        $response->getBody()->write(json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
        
        $response = $response->withStatus($status)
            ->withHeader('Content-Type', 'application/json');
        
        foreach ($headers as $name => $value) {
            $response = $response->withHeader($name, $value);
        }
        
        return $response;
    }
}

if (!function_exists('redirect')) {
    /**
     * Create a redirect response.
     *
     * @param string $url The URL to redirect to.
     * @param int $status The HTTP status code (default: 302).
     * @return Response
     */
    function redirect(string $url, int $status = 302): Response
    {
        $response = new SlimResponse();
        return $response
            ->withStatus($status)
            ->withHeader('Location', $url);
    }
}

if (!function_exists('view')) {
    /**
     * Render a template using Plates.
     *
     * @param string $template The template name.
     * @param array $data The data to pass to the template.
     * @param int $status The HTTP status code.
     * @return Response
     */
    function view(string $template, array $data = [], int $status = 200): Response
    {
        $plates = app(League\Plates\Engine::class);
        $response = new SlimResponse();
        
        $response->getBody()->write($plates->render($template, $data));
        
        return $response
            ->withStatus($status)
            ->withHeader('Content-Type', 'text/html; charset=utf-8');
    }
}

if (!function_exists('abort')) {
    /**
     * Throw an HTTP exception.
     *
     * @param int $code The HTTP status code.
     * @param string $message The error message.
     * @param array $details Additional error details.
     * @throws HttpException
     */
    function abort(int $code, string $message = '', array $details = []): void
    {
        $exception = new \Slim\Exception\HttpNotFoundException(app('request'));
        
        if ($code >= 400 && $code < 500) {
            if ($code === 404) {
                $exception = new \Slim\Exception\HttpNotFoundException(app('request'), $message);
            } elseif ($code === 403) {
                $exception = new \Slim\Exception\HttpForbiddenException(app('request'), $message);
            } elseif ($code === 401) {
                $exception = new \Slim\Exception\HttpUnauthorizedException(app('request'), $message);
            } else {
                $exception = new \Slim\Exception\HttpBadRequestException(app('request'), $message);
            }
        } elseif ($code >= 500) {
            $exception = new \Slim\Exception\HttpInternalServerErrorException(app('request'), $message);
        }
        
        if (!empty($details)) {
            $exception->setDetails($details);
        }
        
        throw $exception;
    }
}

if (!function_exists('auth')) {
    /**
     * Get the authenticated user or a specific attribute.
     *
     * @param string|null $key The attribute to get from the authenticated user.
     * @return mixed|\App\Models\User|null
     */
    function auth(string $key = null)
    {
        $user = app('user'); // You'll need to set this in your authentication middleware
        
        if (is_null($key)) {
            return $user;
        }
        
        return $user ? $user->{$key} : null;
    }
}

if (!function_exists('bcrypt')) {
    /**
     * Hash the given value.
     *
     * @param string $value The value to hash.
     * @param array $options Options for the hashing algorithm.
     * @return string
     */
    function bcrypt(string $value, array $options = []): string
    {
        return password_hash(
            $value,
            PASSWORD_BCRYPT,
            array_merge(['cost' => 12], $options)
        );
    }
}

if (!function_exists('now')) {
    /**
     * Create a new Carbon instance for the current time.
     *
     * @param string|null $time A date/time string.
     * @param string|DateTimeZone|null $tz A timezone identifier or DateTimeZone object.
     * @return \Carbon\Carbon
     */
    function now($time = null, $tz = null)
    {
        return \Carbon\Carbon::parse($time ?? 'now', $tz);
    }
}

if (!function_exists('storage_path')) {
    /**
     * Get the path to the storage directory.
     *
     * @param string $path The path to append to the storage directory.
     * @return string
     */
    function storage_path(string $path = ''): string
    {
        return __DIR__ . '/../storage' . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('base_path')) {
    /**
     * Get the path to the base of the application.
     *
     * @param string $path The path to append to the base directory.
     * @return string
     */
    function base_path(string $path = ''): string
    {
        return __DIR__ . '/..' . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('public_path')) {
    /**
     * Get the path to the public directory.
     *
     * @param string $path The path to append to the public directory.
     * @return string
     */
    function public_path(string $path = ''): string
    {
        return base_path('public') . ($path ? '/' . ltrim($path, '/') : '');
    }
}

if (!function_exists('asset')) {
    /**
     * Generate an asset URL.
     *
     * @param string $path The path to the asset.
     * @param bool $secure Whether to use HTTPS.
     * @return string
     */
    function asset(string $path, bool $secure = null): string
    {
        $baseUrl = rtrim(config('app.url', 'http://localhost'), '/');
        
        if (is_null($secure)) {
            $secure = parse_url($baseUrl, PHP_URL_SCHEME) === 'https';
        }
        
        $baseUrl = $secure ? str_replace('http://', 'https://', $baseUrl) : $baseUrl;
        
        return $baseUrl . '/' . ltrim($path, '/');
    }
}

if (!function_exists('csrf_field')) {
    /**
     * Generate a CSRF token form field.
     *
     * @return string
     */
    function csrf_field(): string
    {
        $token = app('csrf')->getToken();
        return '<input type="hidden" name="' . $token->getValue() . '" value="1">';
    }
}

if (!function_exists('csrf_token')) {
    /**
     * Get the CSRF token value.
     *
     * @return string
     */
    function csrf_token(): string
    {
        return app('csrf')->getToken()->getValue();
    }
}

if (!function_exists('old')) {
    /**
     * Retrieve an old input item.
     *
     * @param string $key The input key.
     * @param mixed $default The default value.
     * @return mixed
     */
    function old(string $key, $default = null)
    {
        $flash = app('flash');
        return $flash->getFirstMessage('old.' . $key, $default);
    }
}

if (!function_exists('session')) {
    /**
     * Get / set the session value for the given key.
     *
     * @param string $key The session key.
     * @param mixed $default The default value.
     * @return mixed|void
     */
    function session(string $key = null, $default = null)
    {
        $session = app('session');
        
        if (is_null($key)) {
            return $session;
        }
        
        if (func_num_args() === 1) {
            return $session->get($key, $default);
        }
        
        $session->set($key, $default);
    }
}

if (!function_exists('trans')) {
    /**
     * Translate the given message.
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function trans(string $key, array $replace = [], string $locale = null): string
    {
        $translator = app('translator');
        
        // If the translator is not available, return the key
        if (!$translator) {
            return $key;
        }
        
        // Replace placeholders in the translation
        $translation = $translator->trans($key, [], 'messages', $locale);
        
        if (!empty($replace)) {
            foreach ($replace as $param => $value) {
                $translation = str_replace(":$param", (string)$value, $translation);
                $translation = str_replace(':' . strtoupper($param), strtoupper((string)$value), $translation);
                $translation = str_replace(':' . ucfirst($param), ucfirst((string)$value), $translation);
            }
        }
        
        return $translation;
    }
}

if (!function_exists('__')) {
    /**
     * Alias for the trans() function.
     *
     * @param string $key
     * @param array $replace
     * @param string|null $locale
     * @return string
     */
    function __(string $key, array $replace = [], string $locale = null): string
    {
        return trans($key, $replace, $locale);
    }
}

if (!function_exists('flash')) {
    /**
     * Flash a message to the session.
     *
     * @param string $key The flash key.
     * @param string $message The flash message.
     * @return void
     */
    function flash(string $key, string $message): void
    {
        app('flash')->addMessage($key, $message);
    }
}
