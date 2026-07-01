<?php

namespace common\components;

use common\models\Journal;
use common\models\JournalMl;

class DoiHelper
{

    public static function generateJournalDoiPrefix(Journal $journal): string
    {
        return substr(trim($journal->getDisplayTitle()), 0, 1) . '.' . $journal->number . '.' . $journal->year . '.' . $journal->id;
    }

}