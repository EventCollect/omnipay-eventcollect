<?php

namespace Omnipay\EventCollect\Message;

class PurchaseResponse extends Response implements AdverseActionNoticeResponseInterface
{
    /**
     * @inheritDoc
     */
    public function requiresAdverseActionNotice(): bool
    {
        return ($this->data['next_action']['type'] ?? null) === 'display_adverse_action_notice';
    }

    /**
     * @inheritDoc
     */
    public function getAdverseActionNotice(string $format = 'html'): ?string
    {
        if (! $this->requiresAdverseActionNotice()) {
            return null;
        }

        return $this->data['next_action']['display_adverse_action_notice'][$format] ?? null;
    }
}
