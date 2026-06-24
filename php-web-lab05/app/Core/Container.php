<?php
namespace App\Core;

class Container
{
    private static array $services = [];
    private static array $instances = [];

    public static function set(string $name, callable $resolver): void
    {
        self::$services[$name] = $resolver;
    }

    public static function has(string $name): bool
    {
        return isset(self::$services[$name]);
    }

    public static function get(string $name)
    {
        if (isset(self::$instances[$name])) {
            return self::$instances[$name];
        }

        if (self::has($name)) {
            self::$instances[$name] = self::$services[$name]();
            return self::$instances[$name];
        }

        throw new \Exception("Service {$name} chưa được đăng ký trong Container.");
    }
}