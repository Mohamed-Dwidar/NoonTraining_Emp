<?php

namespace Modules\StudentModule\Exports;

/** Excel sheet names: max 31 chars, no : \ / ? * [ ], unique within the workbook. */
trait UniqueSheetTitles
{
    private function uniqueTitle(string $title, array $usedTitles): string
    {
        $title = preg_replace('/[:\\\\\/\?\*\[\]]/', '-', $title);
        $title = mb_substr($title, 0, 31);

        $base = $title;
        $i = 2;
        while (in_array($title, $usedTitles, true)) {
            $suffix = ' (' . $i . ')';
            $title = mb_substr($base, 0, 31 - mb_strlen($suffix)) . $suffix;
            $i++;
        }

        return $title;
    }
}
