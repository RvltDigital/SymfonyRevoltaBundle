<?php

namespace RvltDigital\SymfonyRevoltaBundle\Service;

use Monolog\Handler\SlackHandler;
use Monolog\Logger;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SlackLogger extends SlackHandler
{

    /**
     * @var array
     */
    private $config = [];

    public function __construct(?array $config)
    {
        $this->config = $config ?? [];
        parent::__construct(
            $this->config['token'] ?? '',
            $this->config['channel'] ?? '#errors',
            $this->config['bot_name'] ?? 'Errors Service',
            $this->config['use_attachment'] ?? true,
            $this->config['icon_emoji'] ?? ':poop:',
            $this->convertNameToLevel($this->config['level'] ?? '') ?? Logger::WARNING,
            true,
            false,
            $this->config['include_extra'] ?? true,
            []
        );
    }

    protected function write(array $record)
    {
        $ignoreAll = $this->config['exclude_all_404'] ?? false;
        $ignoredPaths = $this->config['excluded_404'] ?? [];
        if (isset($record['context']['exception'])) {
            $exception = $record['context']['exception'];
            if ($exception instanceof NotFoundHttpException) {
                if ($ignoreAll) {
                    return;
                }
                $path = $_SERVER['REQUEST_URI'];
                foreach ($ignoredPaths as $ignoredPath) {
                    if (preg_match("@{$ignoredPath}@", $path)) {
                        return;
                    }
                }
            }
        }
        parent::write($record);
    }

    private function convertNameToLevel(string $level): ?int
    {
        $level = strtolower($level);
        switch ($level) {
            case 'debug':
                return Logger::DEBUG;
            case 'info':
                return Logger::INFO;
            case 'notice':
                return Logger::NOTICE;
            case 'warning':
                return Logger::WARNING;
            case 'error':
                return Logger::ERROR;
            case 'critical':
                return Logger::CRITICAL;
            case 'alert':
                return Logger::ALERT;
            case 'emergency':
                return Logger::EMERGENCY;
            default:
                return null;
        }
    }
}
