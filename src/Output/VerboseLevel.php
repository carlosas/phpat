<?php

namespace PhpAT\Output;

class VerboseLevel
{
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
