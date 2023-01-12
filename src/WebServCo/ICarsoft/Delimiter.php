<?php

declare(strict_types=1);

namespace WebServCo\ICarsoft;

final class Delimiter
{
    public const LINE = "\n";

    public const HEADER_SECTION = '========================================================================';
    public const HEADER_DATA = ':';

    public const TITLE_SECTION = "\n";
    public const TITLE_DATA = '>';

    public const INFO_ITEMS = ',';
    public const INFO_DATA = ':';

    public const CONTENT_DATA = ':';

    public const FRAME_SECTION = '- - - - - - - - - - - - - - - - - - - - - - -' .
    ' - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -';
    public const FRAME_DATA = '-----';
}
