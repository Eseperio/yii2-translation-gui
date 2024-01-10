<?php

namespace eseperio\translatemanager\extractors;

use eseperio\translatemanager\traits\ExtractorTrait;
use yii\i18n\GettextMessageSource;
use yii\i18n\PhpMessageSource;

/**
 * Methods for extracting messages from php files in Gettextmessagesource are protected
 * so we need to extend it to access them.
 */
class GettextMessageExtractor extends GettextMessageSource
{
 use ExtractorTrait;
}
