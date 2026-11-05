<?php

namespace Omnipay\EventCollect\Message;

interface AdverseActionNoticeResponseInterface
{
    /**
     * Whether the customer must be shown an adverse action notice.
     */
    public function requiresAdverseActionNotice(): bool;

    /**
     * The adverse action notice to show the customer, in the given format.
     */
    public function getAdverseActionNotice(string $format = 'html'): ?string;
}
