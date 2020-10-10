<?php

namespace PhpAT\Output;

class VerboseLevel
{
    public const AVAILABLE_LEVELS = [self::NORMAL, self::VERBOSE, self::VERY_VERBOSE];
    public const DEFAULT_LEVEL = self::VERBOSE;
    public const NORMAL = 0;
    public const VERBOSE = 1; //-v
    public const VERY_VERBOSE = 2; //-vv

    public const OUTPUT_LEVEL = [
        VerboseLevel::NORMAL => [
            OutputLevel::DEFAULT,
            OutputLevel::WARNING,
            OutputLevel::ERROR
        ],
        VerboseLevel::VERBOSE => [
            OutputLevel::INFO,
            OutputLevel::DEFAULT,
            OutputLevel::WARNING,
            OutputLevel::ERROR
        ],
        VerboseLevel::VERY_VERBOSE => [
            OutputLevel::DEBUG,
            OutputLevel::INFO,
            OutputLevel::DEFAULT,
            OutputLevel::WARNING,
            OutputLevel::ERROR
        ]
    ];
}
